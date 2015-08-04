var scoData = null; // a bound reference to xp.scoData

$(function(){
	var $iFrame = $('iframe'),
		startPage = $iFrame.attr('src'),
		startTime = new Date().getTime();

	// Stop the iframe from loading (we'll load it after LMS init)
	$iFrame.attr('src', '');

	// Resolves to "2004", "12", or false
	var apiVersion = (function(){
		function getIsScormApi(apiName, win){
			var tries = 0;

			if (!win){win = window;}

			// Look for API in each parent window
			while (!(apiName in win) && win.parent && win.parent!=win){
				tries++; 
				if (tries > 20){
					return false;
				}
				win = win.parent; 
			}
			// Not found yet, try looking through opener windows
			if (!(apiName in win) && win.opener){
				return getIsScormApi(apiName, win.opener);
			}

			return (apiName in win);
		}

		if (getIsScormApi('API_1484_11')){return '2004';}
		if (getIsScormApi('API')){return '12';}
		return false;
	}());

	// Ditch the frameset if no scorm, or load appropriate scorm wrapper
	if (!apiVersion){
		// No SCORM API, ditch frameset
		location.replace(startPage);
	}
	else{
		// Include ADL SCORM API Wrapper
		$.ajax({
			url: '../common/vendor/SCORM_'+apiVersion+'_APIWrapper.js', 
			dataType: 'script',
			cache: true,
			complete: function(){
				// Remap output variable from ADL Wrapper files
				output = {
					log: function(str){
						if (window.console){
							console.log(str);
						}
					}
				};

				// Initialize LMS connection and read in values
				if (apiVersion=='2004'){
					doInitialize();

					if (doGetValue('cmi.entry')=='resume'){
						scoData = JSON.parse(doGetValue('cmi.suspend_data'));
						scoData.bookmark  = doGetValue('cmi.location');
					}
				}
				else if (apiVersion=='12'){
					doLMSInitialize();

					if (doLMSGetValue('cmi.core.entry')=='resume'){
						scoData = JSON.parse(doLMSGetValue('cmi.suspend_data'));
						scoData.bookmark = doLMSGetValue('cmi.core.lesson_location');
					}
				}

				// Load content
				$iFrame.attr('src', startPage);
			}
		});
	}

	// Convert milliseconds to "timeinterval" or "CMITimespan"
	function msToScormTime(ms){
		var dtm = new Date();
		dtm.setTime(ms);

		if (apiVersion=='2004'){
			// Convert duration from milliseconds (n) to P[yY][mM][dD][T[hH][mM][s[.s]S]] format
			// This number format, called "timeinterval" applies to SCORM 2004 implementations
			return 'PT'+Math.floor(ms/3600000)+'H'+dtm.getMinutes().toString()+'M'+dtm.getSeconds().toString()+'S';
		}
		else if (apiVersion=='12'){
			// Convert duration from milliseconds (n) to 0000:00:00.00 format
			// This number format, called "CMITimespan" applies only to SCORM 1.* implementations
			var h = '000' + Math.floor(ms / 3600000),
				m = '0' + dtm.getMinutes(),
				s = '0' + dtm.getSeconds(),
				cs = '0' + Math.round(dtm.getMilliseconds() / 10);
			return h.substr(h.length-4)+':'+m.substr(m.length-2)+':'+s.substr(s.length-2)+'.'+cs.substr(cs.length-2);
		}
	}

	function getScoScore(){
		var isPreTest = ('pre' in scoData.test),
			isPostTest = ('post' in scoData.test);

		if (isPreTest && isPostTest){
			if (scoData.test.pre.score.user >= scoData.test.pre.score.passing){
				// Passed pre-test, use highest test score
				if (scoData.test.pre.score.user > scoData.test.post.score.user){
					return scoData.test.pre.score.user;
				}
				else{
					return scoData.test.post.score.user;
				}
			}
			else{
				// Didn't take (or pass) pre, return post score
				return scoData.test.post.score.user;
			}
		}
		if (!isPreTest && isPostTest){
			return scoData.test.post.score.user;
		}

		return 0;
	}

	// Returns a scorm moniker (string) for the given type of status
	// Allowed types are: success_status; completion_status; completion_status
	//
	// Each scorm status type has it's own list of possible values
	// 
	// lesson_status: passed; completed; failed; incomplete; browsed; not attempted
	// success_status: passed; failed; unknown
	// completion_status: completed; incomplete; not attempted; unknown
	function getScoStatus(type){
		var statuses = {
				lesson_status: 'incomplete',
				success_status: 'unknown',
				completion_status: 'unknown'
			},
			isPreTest = ('pre' in scoData.test),
			isPostTest = ('post' in scoData.test),
			isAllPagesVisited = (function(){
				var i = 0, pageCount = scoData.content.pages.length;
				for (i=0; i<pageCount; i++){
					if (!scoData.content.pages[i].isVisited){
						return false;
					}
				}
				return true;
			}());

		if (isPostTest){
			if ((isPreTest && scoData.test.pre.score.user >= scoData.test.pre.score.passing)
				|| scoData.test.post.score.user >= scoData.test.post.score.passing){
				statuses.lesson_status = 'passed';
				statuses.success_status = 'passed';
				statuses.completion_status = 'completed';
			}
			else{
				statuses.lesson_status = 'failed';
				statuses.success_status = 'failed';
				statuses.completion_status = 'incomplete';
			}
		}	
		else{
			if (isAllPagesVisited){
				statuses.lesson_status = 'completed';
				statuses.success_status = 'passed';
				statuses.completion_status = 'completed';
			}
			else{
				statuses.lesson_status = 'completed';
				statuses.success_status = 'unknown';
				statuses.completion_status = 'incomplete';
			}
		}

		return (statuses.hasOwnProperty(type)) ? statuses[type] : '';
	}

	// Close connection to LMS
	$(window).unload(function(){
		if (apiVersion=='2004'){
			doSetValue('cmi.success_status', getScoStatus('success_status'));
			doSetValue('cmi.completion_status', getScoStatus('completion_status'));
			doSetValue('cmi.score.scaled', (getScoScore()*.01));
			doSetValue('cmi.location', scoData.bookmark);
			doSetValue(
				'cmi.session_time',
				msToScormTime(Math.round(new Date().getTime() - startTime))
			);
			doSetValue('cmi.suspend_data', JSON.stringify(scoData));
			doSetValue('cmi.exit', 'suspend');

			doTerminate();
		}
		else if (apiVersion=='12'){
			doLMSSetValue('cmi.core.lesson_status', getScoStatus('lesson_status'));
			doLMSSetValue('cmi.core.score.min', 0);
			doLMSSetValue('cmi.core.score.max', 100);
			doLMSSetValue('cmi.core.score.raw', getScoScore());
			doLMSSetValue('cmi.core.lesson_location', scoData.bookmark);
			doLMSSetValue(
				'cmi.core.session_time',
				msToScormTime(Math.round(new Date().getTime() - startTime))
			);
			doLMSSetValue('cmi.suspend_data', JSON.stringify(scoData));
			doLMSSetValue('cmi.core.exit', 'suspend');

			doLMSFinish();
		}
	});
});