/* START Plugins */

	/**
	 * jQuery.ScrollTo - Easy element scrolling using jQuery.
	 * Copyright (c) 2007-2009 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
	 * Dual licensed under MIT and GPL.
	 * Date: 5/25/2009
	 * @author Ariel Flesler
	 * @version 1.4.2
	 *
	 * http://flesler.blogspot.com/2007/10/jqueryscrollto.html
	 */
	;(function(d){var k=d.scrollTo=function(a,i,e){d(window).scrollTo(a,i,e)};k.defaults={axis:'xy',duration:parseFloat(d.fn.jquery)>=1.3?0:1};k.window=function(a){return d(window)._scrollable()};d.fn._scrollable=function(){return this.map(function(){var a=this,i=!a.nodeName||d.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!i)return a;var e=(a.contentWindow||a).document||a.ownerDocument||a;return d.browser.safari||e.compatMode=='BackCompat'?e.body:e.documentElement})};d.fn.scrollTo=function(n,j,b){if(typeof j=='object'){b=j;j=0}if(typeof b=='function')b={onAfter:b};if(n=='max')n=9e9;b=d.extend({},k.defaults,b);j=j||b.speed||b.duration;b.queue=b.queue&&b.axis.length>1;if(b.queue)j/=2;b.offset=p(b.offset);b.over=p(b.over);return this._scrollable().each(function(){var q=this,r=d(q),f=n,s,g={},u=r.is('html,body');switch(typeof f){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(f)){f=p(f);break}f=d(f,this);case'object':if(f.is||f.style)s=(f=d(f)).offset()}d.each(b.axis.split(''),function(a,i){var e=i=='x'?'Left':'Top',h=e.toLowerCase(),c='scroll'+e,l=q[c],m=k.max(q,i);if(s){g[c]=s[h]+(u?0:l-r.offset()[h]);if(b.margin){g[c]-=parseInt(f.css('margin'+e))||0;g[c]-=parseInt(f.css('border'+e+'Width'))||0}g[c]+=b.offset[h]||0;if(b.over[h])g[c]+=f[i=='x'?'width':'height']()*b.over[h]}else{var o=f[h];g[c]=o.slice&&o.slice(-1)=='%'?parseFloat(o)/100*m:o}if(/^\d+$/.test(g[c]))g[c]=g[c]<=0?0:Math.min(g[c],m);if(!a&&b.queue){if(l!=g[c])t(b.onAfterFirst);delete g[c]}});t(b.onAfter);function t(a){r.animate(g,j,b.easing,a&&function(){a.call(this,n,b)})}}).end()};k.max=function(a,i){var e=i=='x'?'Width':'Height',h='scroll'+e;if(!d(a).is('html,body'))return a[h]-d(a)[e.toLowerCase()]();var c='client'+e,l=a.ownerDocument.documentElement,m=a.ownerDocument.body;return Math.max(l[h],m[h])-Math.min(l[c],m[c])};function p(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);

/* END Plugins */

$(document).ready(function(){
	// Nav smooth scroll
	$('#header nav, footer').on('click', 'a', function(event){		
		var id = $(this).attr('href');
		
		if (Modernizr.history){
			window.history.replaceState(null, document.title, (id=='#header') ? '/' : id);
		}
		
		$.scrollTo(id, 'slow');
		
		event.preventDefault();
	});
	
	// Projects
	(function(){
		var $projects = $('#projects').find('h4').wrapInner('<a href="#"><span /></a>').end(),
			$sections = $projects.find('section'),
			$divs = $projects.find('section div'),
			sectionCount = $sections.length,
			speed = 300;
			
		// Initially mark aria-expanded as false where app
		$sections.filter('.collapsed').attr('aria-expanded', false);
		
		$projects.on('click', 'header a, header ul', function(event){
			var index = $sections.index($(this).parentsUntil($projects, 'section')),
				$section,
				$div,
				i = 0;
				
			for (i=0; i<sectionCount; i++){
				$section = $($sections[i]);
				$div = $($divs[i]);
				
				if ($section.hasClass('collapsed')){
					if (index==i){
						$div.slideDown(speed);
						$section.removeClass('collapsed').attr('aria-expanded', true);
					}
				}
				else{
					$div.slideUp(
						speed,
						function($el){
							return function () {
								$el.addClass('collapsed').attr('aria-expanded', false);
							}
						}($section)
					);
				}
			}

			event.preventDefault();
		});
	}());
	
	// Contact
	(function(){
		var $contact = $('#contact'),
			$fields = $contact.find('input, textarea'),
			$status = $('<p/>', {'class':'status', 'aria-live':'assertive'}).appendTo(
				$contact.find('form')
			),
			isEmail = function(emailString){
				var reg = new RegExp("^[0-9a-zA-Z]+@[0-9a-zA-Z]+[\.]{1}[0-9a-zA-Z]+[\.]?[0-9a-zA-Z]+$");
				return reg.test(emailString);
			},
			isPlaceholderValue = function($control){
				return ($control.val()==$control.attr('placeholder'));
			};
		
		// Polyfill for placeholder attribute
		if (!Modernizr.input.placeholder){
			$contact.on({
				'focusin': function(){
					var $input = $(this);
					if (isPlaceholderValue($input)){
						$input.val('');
						$input.removeClass('placeholder');
					}
				},
				'focusout': function(){
					var $input = $(this);
					if ($.trim($input.val())==''){
						$input.val($input.attr('placeholder'));
					}
					if (isPlaceholderValue($input)){
						$input.addClass('placeholder');
					}
				}
			}, '[placeholder]').find('[placeholder]').blur();
		}
		
		// Submit form
		$contact.find('form').submit(function(){
			var isValid = true, $form = $(this);
			
			$status.hide().removeClass('success');
			
			// All text input fields are required
			if (!Modernizr.input.required){
				$fields.filter('[placeholder]').each(function(){
					var $field = $(this);

					if ($.trim($field.val())=='' || isPlaceholderValue($field)){
						$status.html($field.attr('placeholder')+' is required.').show();
						isValid = false;
						return false;
					}
				});
			}
			
			// Make sure we have a valid email format
			if (isValid && !Modernizr.inputtypes.email){
				if (!isEmail($fields.filter('[name="email"]').val())){
					isValid = false;
					$status.html('Invalid email.').show();
				}
			}
			
			if (isValid){
				// Ajaxify email submit
				$.ajax({
					type: $form.attr('method'),
					url: $form.attr('action'),
					data: (function(){
						var data = {
							'is-ajax':true
						};
						$fields.each(function(){
							var $field = $(this);
							if ($field.attr('type')=='checkbox'){
								data[$field.attr('name')] = $field.prop('checked') ? 1 : 0;
							}
							else {
								data[$field.attr('name')] = $field.val()
							}
						});
						return data;
					}()),
					success: function(response){
						var response = $.parseJSON(response);
						
						if (response.is_success){
							$status.addClass('success');
						}
						$status.html(response.message).show();
					}
				});
			}
			
			return false; // prevent default form submit action
		})
	}());
	
	// Social Links
	(function(){
		var $tooltip = $('<div/>', {id:'social-tooltip'}).appendTo('body');
		
		// Return 'left' or 'right' (which side of the screen is this link on?)
		function getSide($link){
			var side = $link.data('side');
			
			if (!side){
				side = ($link.parents('ul').hasClass('list-b')) ? 'right' : 'left';
				$link.data('side', side);
			}
			
			return side;
		}

		// Add animated hover effect to social links. Return list of links.
		var links = $('#social').on({
			'mouseenter': function(){
				var $link = $(this), side = getSide($link);

				$link.stop().animate({'margin-left':'0'}, 200, function(){
					$tooltip.html($link.html()).css(
						'top', $link.offset().top + 12
					).css(side, 50).fadeIn('fast');
				});
			},
			'mouseleave': function(){
				var $link = $(this), side = getSide($link);

				$link.stop().animate(
					{'margin-left':(side=='left') ? '-20px' : '20px'},
					100,
					function(){
						$tooltip.hide().css({'left':'auto', 'right':'auto'});
					}
				);
			}
		}, 'a').find('a');

		// Animate tucking social links into sides of screen
		var i, iMax = links.length/2;
		for (i=0; i<iMax; i++){
			(function (links){
				setTimeout(function(){$(links).mouseleave();}, 100*i);
			})([links[i], links[i+iMax]]);
		}
	}());
});