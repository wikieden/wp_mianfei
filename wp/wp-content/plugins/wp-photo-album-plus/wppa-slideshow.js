// Slide show variables and functions
// This is wppa-slideshow.js version 4.3.8
//
// Vars. The vars that have a name that starts with an underscore is an internal var
// The vars without leading underscore are 'external' and get a value from html

// 'External' variables
var wppaFullValignFit = new Array();
var wppaFullFrameDelta = new Array();
var wppaAnimationSpeed;
var wppaImageDirectory;
var wppaAutoColumnWidth = false;
var wppaThumbnailAreaDelta;
var wppaSlideShowTimeOut = 2500;
var wppaFadeInAfterFadeOut = false;
var wppaTextFrameDelta = 0;
var wppaBoxDelta = 0;
var wppaPreambule;
var wppaThumbnailPitch = new Array();
var wppaFilmStripLength = new Array();
var wppaFilmStripMargin = new Array();
var wppaFilmStripAreaDelta = new Array();
var wppaFilmShowGlue;
var wppaIsMini = new Array();
var wppa_portrait_only = new Array();
var wppaSlideShow;				// = 'Slideshow' or its translation
var wppaPhoto;					// = 'Photo' or its translation
var wppaOf;						// = 'of' or its translation
var wppaNextPhoto;				// = 'Next photo' or its translation
var wppaPreviousPhoto;			// = 'Previous photo' or its translation
var wppaNextP;
var wppaPrevP;
var wppaStart = 'Start';		// defaults
var wppaStop = 'Stop';			//
var wppaPleaseName;
var wppaPleaseEmail;
var wppaPleaseComment;
var wppaRatingOnce = true;
var wppaUserName;
var wppaBGcolorNumbar = 'transparent';
var wppaBcolorNumbar = 'transparent';
var wppaBGcolorNumbarActive = 'transparent';
var wppaBcolorNumbarActive = 'transparent';
var wppaNumbarMax = '10';
var wppaAjaxUrl = '';
var wppaNextOnCallback = false;
var wppaRatingUseAjax = false;
var wppaStarOpacity = 0.2;
var wppaTickImg = new Image(); 
var wppaClockImg = new Image();
var wppaSlideWrap = true;
var wppaLightBox = '';
var wppaEmailRequired = true;
var wppaSlideBorderWidth = 0;
var wppaSlideInitRunning = new Array();

// 'Internal' variables
var _wppaPhotoIds = new Array();
var _wppaPhotoAverages = new Array();
var _wppaPhotoMyRating = new Array();
var _wppaVoteReturnUrl = new Array();
var _wppaInWidgetLinkUrl = new Array();
var _wppaInWidgetLinkTitle = new Array();
var _wppaCommentHtml = new Array();
var _wppaIptcHtml = new Array();
var _wppaExifHtml = new Array();
var _wppaToTheSame = false;
var _wppaSlides = new Array();
var _wppaNames = new Array();
var _wppaDescriptions = new Array();
var _wppaCurrentIndex = new Array();
var _wppaNextIndex = new Array();
var _wppaTimeOut = new Array();
var _wppaSlideShowRuns = new Array();
var _wppaForeground = new Array();
var _wppaTogglePending = new Array();
var _wppaIsBusy = new Array();
var _wppaFirst = new Array();
var _wppaVoteInProgress = false;
var _wppaTextDelay;
var _wppaUrl = new Array();
var _wppaLastVote = 0;
var _wppaSkipRated = new Array();
var _wppaLbTitle = new Array();
var _wppaStateCount = 0;
var _wppaDidGoto = new Array();

jQuery(document).ready(function(){
	_wppaLog('ready', 0);
	if (wppaAutoColumnWidth) _wppaDoAutocol(0);
	_wppaTextDelay = wppaAnimationSpeed;
	if (wppaFadeInAfterFadeOut) _wppaTextDelay *= 2;
});

// First the external entrypoints that may be called directly from HTML
// These functions check the validity and store the users request to be executed later if busy and if applicable.

// This is an entrypoint to load the slide data
function wppaStoreSlideInfo(mocc, id, url, size, width, height, name, desc, photoid, avgrat, myrat, rateurl, iwlinkurl, iwlinktitle, iwtimeout, commenthtml, iptchtml, exifhtml, lbtitle) {
	if ( ! _wppaSlides[mocc] ) {
		_wppaSlides[mocc] = new Array();
		_wppaNames[mocc] = new Array();
		_wppaDescriptions[mocc] = new Array();
		_wppaCurrentIndex[mocc] = -1;
		_wppaNextIndex[mocc] = 0;
		if (parseInt(iwtimeout) > 0) _wppaTimeOut[mocc] = parseInt(iwtimeout);
		else _wppaTimeOut[mocc] = wppaSlideShowTimeOut;
		_wppaSlideShowRuns[mocc] = false;
		_wppaTogglePending[mocc] = -2;	// -2 means NO, index for _wppaStartStop otherwise
		_wppaForeground[mocc] = 0;
		_wppaIsBusy[mocc] = false;
		_wppaFirst[mocc] = true;
		wppaFullValignFit[mocc] = false;
		_wppaPhotoIds[mocc] = new Array();
		_wppaPhotoAverages[mocc] = new Array();
		_wppaPhotoMyRating[mocc] = new Array();
		_wppaVoteReturnUrl[mocc] = new Array();
		wppa_portrait_only[mocc] = false;
		_wppaInWidgetLinkUrl[mocc] = new Array(); // iwlinkurl;
		_wppaInWidgetLinkTitle[mocc] = new Array(); // iwlinktitle;
		_wppaCommentHtml[mocc] = new Array();
		_wppaIptcHtml[mocc] = new Array();
		_wppaExifHtml[mocc] = new Array();
		_wppaUrl[mocc] = new Array();
		_wppaSkipRated[mocc] = false;
		_wppaLbTitle[mocc] = new Array();
		_wppaDidGoto[mocc] = false;
	}
    _wppaSlides[mocc][id] = ' src="' + url + '" alt="' + name + '" class="theimg big" ';
		// Add 'old' width and height only for non-auto
		if ( ! wppaAutoColumnWidth ) _wppaSlides[mocc][id] += 'width="' + width + '" height="' + height + '" ';
	_wppaSlides[mocc][id] += 'style="' + size + '; display:block;">';
    _wppaNames[mocc][id] = name;
    _wppaDescriptions[mocc][id] = desc;
	_wppaPhotoIds[mocc][id] = photoid;		// reqd for rating and comment
	_wppaPhotoAverages[mocc][id] = avgrat;		// avg ratig value
	_wppaPhotoMyRating[mocc][id] = myrat;		// my rating
	_wppaVoteReturnUrl[mocc][id] = rateurl;		// url that performs the vote and returns to the page
	_wppaInWidgetLinkUrl[mocc][id] = iwlinkurl;
	_wppaInWidgetLinkTitle[mocc][id] = iwlinktitle;
	_wppaCommentHtml[mocc][id] = commenthtml;
	_wppaIptcHtml[mocc][id] = iptchtml;
	_wppaExifHtml[mocc][id] = exifhtml;
	_wppaUrl[mocc][id] = url;
	_wppaLbTitle[mocc][id] = lbtitle;
}

function wppaSpeed(mocc, faster) {
	// Can change speed of slideshow only when running
	if ( _wppaSlideShowRuns[mocc] ) {
		_wppaSpeed(mocc, faster);
	}
}

function wppaStopShow(mocc) {
	if ( _wppaSlideShowRuns[mocc] ) {		// Stop it
		_wppaStop(mocc);
	}
}

function wppaStartStop(mocc, index) {
	// The application contains various togglers for start/stop
	// The busy flag will be reset at the end of the NextSlide procedure
	if ( _wppaIsBusy[mocc] ) {					// Busy...
		_wppaTogglePending[mocc] = index;		// Remember there is a toggle pending
	}
	else { 										// Not busy...
		if ( _wppaSlideShowRuns[mocc] ) {		// Stop it
			_wppaStop(mocc);
		}
		else {	// Start it
			_wppaStart(mocc, index);
		}
	}
}

function wppaBbb(mocc, where, act) {
	// Big Browsing Buttons only work when stopped
	if ( ! _wppaSlideShowRuns[mocc] ) {
		_wppaBbb(mocc, where, act);
	}
}

function wppaRateIt(mocc, value) {
	_wppaRateIt(mocc, value);
}

function wppaPrev(mocc) {
	_wppaDidGoto[mocc] = true;
	if ( ! _wppaSlideShowRuns[mocc] ) {
		_wppaPrev(mocc);
	}
}

function wppaNext(mocc) {
	_wppaDidGoto[mocc] = true;
	if ( ! _wppaSlideShowRuns[mocc] ) {
		_wppaNext(mocc);
	}
}

function wppaFollowMe(mocc, idx) {
	if ( ! _wppaSlideShowRuns[mocc] ) {
		_wppaFollowMe(mocc, idx);
	}
}

function wppaLeaveMe(mocc, idx) {
	if ( ! _wppaSlideShowRuns[mocc] ) {
		_wppaLeaveMe(mocc, idx);
	}
}

function wppaGoto(mocc, idx) {
	// Goto the requested slide if the slideshow stopped
	_wppaDidGoto[mocc] = true;
	if ( ! _wppaSlideShowRuns[mocc] ) {
		_wppaGoto(mocc, idx);
	}
}

function wppaGotoKeepState(mocc, idx) {
	// Goto the requested slide and preserve running state
	_wppaDidGoto[mocc] = true;
	_wppaGotoKeepState(mocc, idx);
}
function _wppaGotoKeepState(mocc, idx) {	
	if ( _wppaSlideShowRuns[mocc] ) {
		_wppaGotoRunning(mocc,idx);
	}
	else {
		_wppaGoto(mocc,idx);
	}
}


function wppaGotoRunning(mocc, idx) {
	// Goto the requested slide and start running
	_wppaDidGoto[mocc] = true;
	_wppaGotoRunning(mocc, idx);
}

function wppaValidateComment(mocc) {
	return _wppaValidateComment(mocc);
}

function _wppaNextSlide(mocc, mode) {
	_wppaLog('NextSlide', mocc);

	if ( ! _wppaSlideShowRuns[mocc] && mode == 'auto' ) return; // Kill an old timed request, while stopped
	if ( ! _wppaSlides[mocc] ) return;
	if ( _wppaSlides[mocc].length < 2 && !_wppaFirst[mocc] ) return; //Do not animate single image
	if ( ! _wppaSlideShowRuns[mocc] && mode == 'reset' ) _wppaSlideShowRuns[mocc] = true;

	// No longer busy voting
	_wppaVoteInProgress = false;
	
	// Set the busy flag
	_wppaIsBusy[mocc] = true;

	// Hide metadata while changing image
	if ( _wppaSlideShowRuns[mocc] ) _wppaShowMetaData(mocc, 'hide');
	
	var fg = _wppaForeground[mocc];
	var bg = 1 - fg;

	// Find index of next slide if in auto mode and not stop in progress
	if (_wppaSlideShowRuns[mocc]) {
		_wppaNextIndex[mocc] = _wppaCurrentIndex[mocc] + 1;
		if (_wppaNextIndex[mocc] == _wppaSlides[mocc].length) _wppaNextIndex[mocc] = 0;
	}

	// Set numbar backgrounds
	jQuery('[id^=wppa-numbar-' + mocc + '-]').css('background-color', wppaBGcolorNumbar);
	jQuery('[id^=wppa-numbar-' + mocc + '-]').css('border-color', wppaBcolorNumbar);
	jQuery("#wppa-numbar-" + mocc + "-" + _wppaNextIndex[mocc]).css('background-color', wppaBGcolorNumbarActive);
	jQuery("#wppa-numbar-" + mocc + "-" + _wppaNextIndex[mocc]).css('border-color', wppaBcolorNumbarActive);
	
	// too many? all dots except current
	if (_wppaSlides[mocc].length > wppaNumbarMax) {
		jQuery('[id^=wppa-numbar-' + mocc + '-]').html(' . ');
		jQuery("#wppa-numbar-" + mocc + "-" + _wppaNextIndex[mocc]).html(' ' + (_wppaNextIndex[mocc]+1) + ' ');
	}
	
    // first:
    if (_wppaFirst[mocc]) {
	    if (_wppaCurrentIndex[mocc] != -1) {
			if (_wppaInWidgetLinkUrl[mocc][_wppaCurrentIndex[mocc]] != '') {
				jQuery("#theslide0-"+mocc).html('<a href="'+_wppaInWidgetLinkUrl[mocc][_wppaCurrentIndex[mocc]]+'" title="'+_wppaInWidgetLinkTitle[mocc][_wppaCurrentIndex[mocc]]+'"><img title="'+_wppaNames[mocc][_wppaCurrentIndex[mocc]]+'" id="theimg0-'+mocc+'" '+_wppaSlides[mocc][_wppaCurrentIndex[mocc]]+'</a>');
			}
			else {
				if (wppaLightBox == '') {
					jQuery("#theslide0-"+mocc).html('<img title="'+_wppaNames[mocc][_wppaCurrentIndex[mocc]]+'" id="theimg0-'+mocc+'" '+_wppaSlides[mocc][_wppaCurrentIndex[mocc]]);
				}
				else {
					jQuery("#theslide0-"+mocc).html('<a href="'+_wppaUrl[mocc][_wppaCurrentIndex[mocc]]+'" title="'+_wppaLbTitle[mocc][_wppaCurrentIndex[mocc]]+'" rel="'+wppaLightBox+'"><img title="'+_wppaNames[mocc][_wppaCurrentIndex[mocc]]+'" id="theimg0-'+mocc+'" '+_wppaSlides[mocc][_wppaCurrentIndex[mocc]]+'</a>');
				}
			}
			jQuery("#theimg0-"+mocc).hide();
		}
		if (_wppaInWidgetLinkUrl[mocc][_wppaNextIndex[mocc]] != '') {
			jQuery("#theslide1-"+mocc).html('<a href="'+_wppaInWidgetLinkUrl[mocc][_wppaNextIndex[mocc]]+'" title="'+_wppaInWidgetLinkTitle[mocc][_wppaNextIndex[mocc]]+'"><img title="'+_wppaNames[mocc][_wppaNextIndex[mocc]]+'" id="theimg1-'+mocc+'" '+_wppaSlides[mocc][_wppaNextIndex[mocc]]+'</a>');
		}
		else {
			if (wppaLightBox == '') {
				jQuery("#theslide1-"+mocc).html('<img title="'+_wppaNames[mocc][_wppaNextIndex[mocc]]+'" id="theimg1-'+mocc+'" '+_wppaSlides[mocc][_wppaNextIndex[mocc]]);
			}
			else {
				jQuery("#theslide1-"+mocc).html('<a href="'+_wppaUrl[mocc][_wppaNextIndex[mocc]]+'" title="'+_wppaLbTitle[mocc][_wppaNextIndex[mocc]]+'" rel="'+wppaLightBox+'" ><img title="'+_wppaNames[mocc][_wppaNextIndex[mocc]]+'" id="theimg1-'+mocc+'" '+_wppaSlides[mocc][_wppaNextIndex[mocc]]+'</a>');
			}
		}
		jQuery("#theimg1-"+mocc).hide();	      
	
		_wppaLoadSpinner(mocc);
	    
		// Display name, description and comments
		jQuery("#imagedesc-"+mocc).html('&nbsp;'+_wppaDescriptions[mocc][_wppaCurrentIndex[mocc]]+'&nbsp;');
		jQuery("#imagetitle-"+mocc).html('&nbsp;'+_wppaNames[mocc][_wppaCurrentIndex[mocc]]+'&nbsp;');
		jQuery("#comments-"+mocc).html(_wppaCommentHtml[mocc][_wppaCurrentIndex[mocc]]);
		jQuery("#iptc-"+mocc).html(_wppaIptcHtml[mocc][_wppaCurrentIndex[mocc]]);
		jQuery("#exif-"+mocc).html(_wppaExifHtml[mocc][_wppaCurrentIndex[mocc]]);
		
		// Display counter and arrow texts
		if (document.getElementById('counter-'+mocc)) {
			if (wppaIsMini[mocc]) {
				jQuery('#prev-arrow-'+mocc).html(wppaPrevP);
				jQuery('#next-arrow-'+mocc).html(wppaNextP);
			}
			else {
				jQuery('#prev-arrow-'+mocc).html(wppaPreviousPhoto);
				jQuery('#next-arrow-'+mocc).html(wppaNextPhoto);
			}
		}
    }
    // end first
    else {
    	// load next img (backg)
		if (_wppaInWidgetLinkUrl[mocc][_wppaNextIndex[mocc]] != '') {
			jQuery("#theslide"+bg+"-"+mocc).html('<a href="'+_wppaInWidgetLinkUrl[mocc][_wppaNextIndex[mocc]]+'" title="'+_wppaInWidgetLinkTitle[mocc][_wppaNextIndex[mocc]]+'"><img title="'+_wppaNames[mocc][_wppaNextIndex[mocc]]+'" id="theimg'+bg+'-'+mocc+'" '+_wppaSlides[mocc][_wppaNextIndex[mocc]]+'</a>');
		}
		else {
			if (wppaLightBox == '') {
				jQuery("#theslide"+bg+"-"+mocc).html('<img title="'+_wppaNames[mocc][_wppaNextIndex[mocc]]+'" id="theimg'+bg+'-'+mocc+'" '+_wppaSlides[mocc][_wppaNextIndex[mocc]]);
			}
			else {
				jQuery("#theslide"+bg+"-"+mocc).html('<a href="'+_wppaUrl[mocc][_wppaNextIndex[mocc]]+'" title="'+_wppaLbTitle[mocc][_wppaNextIndex[mocc]]+'" rel="'+wppaLightBox+'" ><img title="'+_wppaNames[mocc][_wppaNextIndex[mocc]]+'" id="theimg'+bg+'-'+mocc+'" '+_wppaSlides[mocc][_wppaNextIndex[mocc]]+'</a>');
			}
		}
		jQuery("#theimg"+bg+"-"+mocc).hide();
    }
	_wppaFirst[mocc] = false;
	
	// See if the filmstrip needs wrap around before shifting to the right location
	_wppaCheckRewind(mocc);

    // Next is now current
    _wppaCurrentIndex[mocc] = _wppaNextIndex[mocc];
	if (wppaAutoColumnWidth) _wppaDoAutocol(mocc);
	// Give free for a while to enable rendering of what we have done so far
	setTimeout('_wppaNextSlide_2('+mocc+')', 10);	// to be continued
}

function _wppaNextSlide_2(mocc) {
	_wppaLog('NextSlide_2', mocc);

	var fg, bg;	

	fg = _wppaForeground[mocc];
	bg = 1 - fg;
	// Wait for load complete
	// If we are here as a result of an onstatechange event, the background image is no longer available and will not become complete
	if (document.getElementById('theimg'+bg+"-"+mocc)) { 
		if (!document.getElementById('theimg'+bg+"-"+mocc).complete) {
			setTimeout('_wppaNextSlide_2('+mocc+')', 100);	// Try again after 100 ms
			return;
		}
	}
	// Remove spinner
	_wppaUnloadSpinner(mocc);
	// Do autocol if required
	if (wppaAutoColumnWidth) _wppaDoAutocol(mocc);
	// Hide subtitles
	if (_wppaSlideShowRuns[mocc] != -1) {	// not stop in progress
		if (!_wppaToTheSame) {
			_wppaShowMetaData(mocc, 'hide');
		}
	}
	// change foreground
	_wppaForeground[mocc] = 1 - _wppaForeground[mocc];
	fg = _wppaForeground[mocc];
	bg = 1 - fg;
	setTimeout('_wppaNextSlide_3('+mocc+')', 10);
}

function _wppaNextSlide_3(mocc) {
	_wppaLog('NextSlide_3', mocc);

	var fg;
	var bg;
	fg = _wppaForeground[mocc];
	bg = 1 - fg;

	// Fade old image out
	jQuery("#theimg"+bg+"-"+mocc).fadeOut(wppaAnimationSpeed); 					// Req'd for change in portrait/landscape vv

	// Fade new image in and continue with step 4
	if (wppaFadeInAfterFadeOut) {
		jQuery("#theimg"+fg+"-"+mocc).delay(wppaAnimationSpeed).fadeIn(wppaAnimationSpeed, _wppaNextSlide_4(mocc)); 
	}
	else {
		jQuery("#theimg"+fg+"-"+mocc).fadeIn(wppaAnimationSpeed, _wppaNextSlide_4(mocc)); 
	}
}

function _wppaNextSlide_4(mocc) {
	_wppaLog('NextSlide_4', mocc);

	// set height to fit if reqd
	if (wppa_portrait_only[mocc]) {
		h = jQuery('#theimg'+_wppaForeground[mocc]+'-'+mocc).css('height');
		jQuery('#slide_frame-'+mocc).css('height', parseInt(h)+'px');
	}
	else if (wppaFullValignFit[mocc]) {
		h = parseInt(jQuery('#theimg'+_wppaForeground[mocc]+'-'+mocc).css('height')) + wppaFullFrameDelta[mocc];
		jQuery('#slide_frame-'+mocc).css('height', h+'px');
		jQuery('.bbb-'+mocc).css('height', h+'px');
		jQuery('#slide_frame-'+mocc).css('minHeight', '0px');
	}

	// Display counter and arrow texts
	if (wppaIsMini[mocc]) {
		jQuery('#counter-'+mocc).html( (_wppaCurrentIndex[mocc]+1)+' / '+_wppaSlides[mocc].length );
	}
	else {
		jQuery('#counter-'+mocc).html( wppaPhoto+' '+(_wppaCurrentIndex[mocc]+1)+' '+wppaOf+' '+_wppaSlides[mocc].length );
	}

	// Update breadcrumb
	jQuery('#bc-pname-'+mocc).html( _wppaNames[mocc][_wppaCurrentIndex[mocc]] );

	// Adjust filmstrip
	var xoffset;
	xoffset = wppaFilmStripLength[mocc] / 2 - (_wppaCurrentIndex[mocc] + 0.5 + wppaPreambule) * wppaThumbnailPitch[mocc] - wppaFilmStripMargin[mocc];
	if (wppaFilmShowGlue) xoffset -= (wppaFilmStripMargin[mocc] * 2 + 2);	// Glue
	jQuery('#wppa-filmstrip-'+mocc).animate({marginLeft: xoffset+'px'});
	
	// Set rating mechanism
	_wppaSetRatingDisplay(mocc);
	
	// Wait for almost next slide
	setTimeout('_wppaNextSlide_5('+mocc+')', _wppaTextDelay); 
}

function _wppaNextSlide_5(mocc) {
	_wppaLog('NextSlide_5', mocc);

	// If we are going to the same slide, there is no need to hide and restore the subtitles and commentframe
	if (!_wppaToTheSame) {	
		// Restore subtitles
		jQuery('#imagedesc-'+mocc).html('&nbsp;' + _wppaDescriptions[mocc][_wppaCurrentIndex[mocc]] + '&nbsp;');
		jQuery('#imagetitle-'+mocc).html('&nbsp;' + _wppaNames[mocc][_wppaCurrentIndex[mocc]] + '&nbsp;');
		// Restore comments html
		jQuery("#comments-"+mocc).html(_wppaCommentHtml[mocc][_wppaCurrentIndex[mocc]]);
		// Restor IPTC
		jQuery("#iptc-"+mocc).html(_wppaIptcHtml[mocc][_wppaCurrentIndex[mocc]]);
		jQuery("#exif-"+mocc).html(_wppaExifHtml[mocc][_wppaCurrentIndex[mocc]]);

	}
	_wppaToTheSame = false;					// This has now been worked out

	// End of non wrapped show?
	if ( _wppaSlideShowRuns[mocc] && ! wppaSlideWrap && ( ( _wppaCurrentIndex[mocc] + 1 ) == _wppaSlides[mocc].length ) ) {  
		_wppaIsBusy[mocc] = false;
		_wppaStop(mocc);	// stop
		return;
	}

	// Re-display the metadata
	_wppaShowMetaData(mocc, 'show'); 
	
	if ( _wppaTogglePending[mocc] != -2 ) {			// A Toggle pending?
		var index = _wppaTogglePending[mocc];		// Remember the pending startstop request argument
		_wppaTogglePending[mocc] = -2;				// Reset the pending toggle
		wppaStartStop(mocc, index);					// Do as if the toggle request happens now
	}
	else {											// No toggle pending
		if (_wppaSlideShowRuns[mocc]) {				// Wait for next slide
			setTimeout('_wppaNextSlide('+mocc+', "auto")', _wppaTimeOut[mocc]); 
		}	
		else {									// Done!
			if ( _wppaDidGoto[mocc] ) {
				wppaPushStateSlide(mocc, _wppaCurrentIndex[mocc]);	// Add to history stack
				_wppaDidGoto[mocc] = false;
			}
		}
	}

	_wppaIsBusy[mocc] = false;					// No longer busy
}
 
function _wppaNext(mocc) {
	_wppaLog('Next', mocc);

	// Check for end of non wrapped show
	if ( ! wppaSlideWrap && _wppaCurrentIndex[mocc] == (_wppaSlides[mocc].length -1) ) return;
	// Find next index
	_wppaNextIndex[mocc] = _wppaCurrentIndex[mocc] + 1;
	if (_wppaNextIndex[mocc] == _wppaSlides[mocc].length) _wppaNextIndex[mocc] = 0;
	// And go!
	_wppaNextSlide(mocc, 0);
}

function _wppaNextOnCallback(mocc) {
	_wppaLog('NextOnCallback', mocc);

	// Check for end of non wrapped show
	if ( ! wppaSlideWrap && _wppaCurrentIndex[mocc] == (_wppaSlides[mocc].length -1) ) return;
	// Check for skip rated after rating
	if ( _wppaSkipRated[mocc] ) {
		var now = _wppaCurrentIndex[mocc];
		var idx = now + 1;
		if (idx == _wppaSlides[mocc].length) idx = 0;	// wrap?
		var next = idx; // assume simple next
		if ( _wppaPhotoMyRating[mocc][next] != 0 ) {		// Already rated, skip
			idx++;	// try next
			if (idx == _wppaSlides[mocc].length) idx = 0;	// wrap?
			while (idx != next && _wppaPhotoMyRating[mocc][idx] != 0) {	// still rated, skip
				idx ++;	// try next
				if (idx == _wppaSlides[mocc].length) idx = 0;	// wrap?
			}	// either idx == next or not rated
			next = idx;
		}
		_wppaNextIndex[mocc] = next;
	}
	else {	// Normal situation
		_wppaNextIndex[mocc] = _wppaCurrentIndex[mocc] + 1;
		if (_wppaNextIndex[mocc] == _wppaSlides[mocc].length) _wppaNextIndex[mocc] = 0;
	}
	_wppaNextSlide(mocc, 0);
}

function _wppaPrev(mocc) {
	_wppaLog('Prev', mocc);
	
	// Check for begin of non wrapped show
	if ( ! wppaSlideWrap && _wppaCurrentIndex[mocc] == 0 ) return;
	// Find previous index
	_wppaNextIndex[mocc] = _wppaCurrentIndex[mocc] - 1;
	if (_wppaNextIndex[mocc] < 0) _wppaNextIndex[mocc] = _wppaSlides[mocc].length - 1;
	// And go!
	_wppaNextSlide(mocc, 0);
}

function _wppaGoto(mocc, idx) {
	_wppaLog('Goto', mocc);
	
	_wppaToTheSame = (_wppaNextIndex[mocc] == idx);
	_wppaNextIndex[mocc] = idx;
	_wppaNextSlide(mocc, 0);
}

function _wppaGotoRunning(mocc, idx) {
	//wait until not bussy
	if (_wppaIsBusy[mocc]) { 
		setTimeout('_wppaGotoRunning('+mocc+',' + idx + ')', 10);	// Try again after 10 ms
		return;
	}
    
	_wppaLog('GotoRunning', mocc);

	_wppaSlideShowRuns[mocc] = false; // we don't want timed loop to occur during our work
    
	_wppaToTheSame = (_wppaNextIndex[mocc] == idx);
	_wppaNextIndex[mocc] = idx;
	_wppaNextSlide(mocc, "manual"); // enqueue new transition
    
	_wppaGotoContinue(mocc);
}

function _wppaGotoContinue(mocc){
	if (_wppaIsBusy[mocc]) {
		setTimeout('_wppaGotoContinue('+mocc+')', 10);	// Try again after 10 ms
		return;
	}
	setTimeout('_wppaNextSlide('+mocc+', "reset")', _wppaTimeOut[mocc] + 10); //restart slideshow after new timeout
}

function _wppaStart(mocc, idx) {
	_wppaLog('Start', mocc);
	
	if ( idx == -2 ) {	// Init at first without my rating
		var i = 0;
		idx = 0;
		_wppaSkipRated[mocc] = true;
		if ( _wppaPhotoMyRating[mocc][i] != 0 ) {
			while (i < _wppaSlides[mocc].length) {
				if ( idx == 0 && _wppaPhotoMyRating[mocc][i] == 0 ) idx = i;
				i++;
			}
		}
	}

	if ( idx > -1 ) {	// Init still at index idx
		jQuery('#startstop-'+mocc).html( wppaStart+' '+wppaSlideShow ); 
		jQuery('#speed0-'+mocc).css('visibility', 'hidden');
		jQuery('#speed1-'+mocc).css('visibility', 'hidden');
		_wppaNextIndex[mocc] = idx;
		_wppaCurrentIndex[mocc] = idx;
		_wppaNextSlide(mocc, 0);
		_wppaShowMetaData(mocc, 'show');
	}
	else {	// idx == -1, start from where you are
		_wppaSlideShowRuns[mocc] = true;
		_wppaNextSlide(mocc, 0);
		jQuery('#startstop-'+mocc).html( wppaStop );
		jQuery('#speed0-'+mocc).css('visibility', 'visible');
		jQuery('#speed1-'+mocc).css('visibility', 'visible');
		_wppaShowMetaData(mocc, 'hide');	
		jQuery('#bc-pname-'+mocc).html(wppaSlideShow);
	}
	
	// Both cases:
	_wppaSetRatingDisplay(mocc);
}

function _wppaStop(mocc) {
	_wppaLog('Stop', mocc);
	
    _wppaSlideShowRuns[mocc] = false;
    jQuery('#startstop-'+mocc).html( wppaStart+' '+wppaSlideShow );  
	jQuery('#speed0-'+mocc).css('visibility', 'hidden');
	jQuery('#speed1-'+mocc).css('visibility', 'hidden');
	_wppaShowMetaData(mocc, 'show');
	jQuery('#bc-pname-'+mocc).html( _wppaNames[mocc][_wppaCurrentIndex[mocc]] );
}

function _wppaSpeed(mocc, faster) {
	_wppaLog('Speed', 0);
	
    if (faster) {
        if (_wppaTimeOut[mocc] > 500) _wppaTimeOut[mocc] /= 1.5;
    }
    else {
        if (_wppaTimeOut[mocc] < 60000) _wppaTimeOut[mocc] *= 1.5;
    }
}

function _wppaLoadSpinner(mocc) {
	_wppaLog('LoadSpinner', mocc);
	
	var top;
	var lft;
	var elm;
	
	top = jQuery('#slide_frame-'+mocc).css('height');
	if (top > 0) {
		top = parseInt(parseInt(top/2) - 4)+'px';
	}
	else {
		top = jQuery('#slide_frame-'+mocc).css('minHeight');
		if (top > 0) {
			top = parseInt(parseInt(top/2) - 4)+'px';
		}
		else top = '150px';
	}
	lft = jQuery('#slide_frame-'+mocc).css('width');

	lft = parseInt(lft);
	if (lft > 0) {
		lft = parseInt(lft/2 - 4)+'px';
	}

	jQuery('#spinner-'+mocc).css('top',top);
	jQuery('#spinner-'+mocc).css('left',lft);
	jQuery('#spinner-'+mocc).html('<img id="spinnerimg-'+mocc+'" src="'+wppaImageDirectory+'wpspin.gif" />');
}

function _wppaUnloadSpinner(mocc) {
	_wppaLog('UnloadSpinner', mocc);
//return; // debug
	jQuery('#spinner-'+mocc).html('');
}

function _wppaDoAutocol(mocc) {
	_wppaLog('DoAutocol', mocc);
	var w;
	var h;
	if (!wppaAutoColumnWidth) return;
	
	w = document.getElementById('wppa-container-1').parentNode.clientWidth;
	
	jQuery(".wppa-container").css('width',w);
	ws = w - 2 * wppaSlideBorderWidth;

	jQuery(".theimg").css('width',ws);
	jQuery(".thumbnail-area").css('width',w - wppaThumbnailAreaDelta);
	wppaFilmStripLength[mocc] = w - wppaFilmStripAreaDelta[mocc];

	jQuery("#filmwindow-"+mocc).css('width',wppaFilmStripLength[mocc]);

	jQuery(".wppa-text-frame").css('width',w - wppaTextFrameDelta);
	jQuery(".wppa-cover-box").css('width',w - wppaBoxDelta);
	
	// See if there are slideframe images
	h = 0;

	if (document.getElementById('theimg0-'+mocc)) {
		h = document.getElementById('theimg0-'+mocc).clientHeight;
	}
	if (h == 0) {
		if (document.getElementById('theimg1-'+mocc)) {
			h = document.getElementById('theimg1-'+mocc).clientHeight;
		}
	}
	// Set slideframe height to the height found (if any)
	if (h > 0) {
		jQuery("#slide_frame-"+mocc).css('height',h);
	}
	else {		// Try again later
		setTimeout('_wppaDoAutocol('+mocc+')', wppaAnimationSpeed);
	}
}

function _wppaCheckRewind(mocc) {
	_wppaLog('CheckRewind', mocc);

	var n_images;
	var n_diff;
	var l_substrate;
	var x_marg;
	
	if (!document.getElementById('wppa-filmstrip-'+mocc)) return; // There is no filmstrip
	
	n_diff = Math.abs(_wppaCurrentIndex[mocc] - _wppaNextIndex[mocc]);
	if (n_diff < 2) return;
	
	var n_images = wppaFilmStripLength[mocc] / wppaThumbnailPitch[mocc];
	
	if (n_diff >= ((n_images + 1) / 2)) {
		l_substrate = wppaThumbnailPitch[mocc] * _wppaSlides[mocc].length;
		if (wppaFilmShowGlue) l_substrate += (2 + 2 * wppaFilmStripMargin[mocc]);
		
		x_marg = parseInt(jQuery('#wppa-filmstrip-'+mocc).css('margin-left'));

		if (_wppaNextIndex[mocc] > _wppaCurrentIndex[mocc]) {
			x_marg -= l_substrate;
		}
		else {
			x_marg += l_substrate;
		}

		jQuery('#wppa-filmstrip-'+mocc).css('margin-left', x_marg+'px');
	}
}

function _wppaSetRatingDisplay(mocc) {
	_wppaLog('setRatingDisplay', mocc);

	var idx, avg, myr;
	if (!document.getElementById('wppa-rating-'+mocc)) return; 	// No rating bar
	// Set Avg rating
	avg = _wppaPhotoAverages[mocc][_wppaCurrentIndex[mocc]];
	_wppaSetRd(mocc, avg, '#wppa-avg-');
	// Set My rating
	myr = _wppaPhotoMyRating[mocc][_wppaCurrentIndex[mocc]];
	_wppaSetRd(mocc, myr, '#wppa-rate-');
}
		
function _wppaSetRd(mocc, avg, where) {
	_wppaLog('SetRd', mocc);
		
	var idx1 = parseInt(avg);
	var idx2 = idx1 + 1;
	var frac = avg - idx1;
	var opac = wppaStarOpacity + frac * (1.0 - wppaStarOpacity);
	var ilow = 1;
	var ihigh = 5;

	for (idx=ilow;idx<=ihigh;idx++) {
		if (where == '#wppa-rate-') {
			jQuery(where+mocc+'-'+idx).attr('src', wppaImageDirectory+'star.png');
		}
		if (idx <= idx1) {
			jQuery(where+mocc+'-'+idx).stop().fadeTo(100, 1.0);
		}
		else if (idx == idx2) {
			jQuery(where+mocc+'-'+idx).stop().fadeTo(100, opac); 
		}
		else {
			jQuery(where+mocc+'-'+idx).stop().fadeTo(100, wppaStarOpacity);
		}
	}
}

function _wppaFollowMe(mocc, idx) {
	_wppaLog('FollowMe', mocc);

	if (_wppaSlideShowRuns[mocc]) return;				// Do not rate on a running show, what only works properly in Firefox								

	if (_wppaPhotoMyRating[mocc][_wppaCurrentIndex[mocc]] != 0 && wppaRatingOnce) return;	// Already rated
	if (_wppaVoteInProgress) return;
	_wppaSetRd(mocc, idx, '#wppa-rate-');
}

function _wppaLeaveMe(mocc, idx) {
	_wppaLog('LeaveMe', mocc);

	if (_wppaSlideShowRuns[mocc]) return;				// Do not rate on a running show, what only works properly in Firefox	

	if (_wppaPhotoMyRating[mocc][_wppaCurrentIndex[mocc]] != 0 && wppaRatingOnce) return;	// Already rated
	if (_wppaVoteInProgress) return;
	_wppaSetRd(mocc, _wppaPhotoMyRating[mocc][_wppaCurrentIndex[mocc]], '#wppa-rate-');
}

function _wppaRateIt(mocc, value) {
	_wppaLog('RateIt', mocc);

	var photoid = _wppaPhotoIds[mocc][_wppaCurrentIndex[mocc]];
	var oldval  = _wppaPhotoMyRating[mocc][_wppaCurrentIndex[mocc]];
	var url 	= _wppaVoteReturnUrl[mocc][_wppaCurrentIndex[mocc]]+'&wppa-rating='+value+'&wppa-rating-id='+photoid;
		url    += '&wppa-nonce='+jQuery('#wppa-nonce').attr('value');
	
	if (_wppaSlideShowRuns[mocc]) return;								// Do not rate a running show								
	if (oldval != 0 && wppaRatingOnce) return;							// Already rated, and once allowed only
																			
	_wppaVoteInProgress = true;											// Keeps opacity as it is now
	_wppaLastVote = value;
	
	jQuery('#wppa-rate-'+mocc+'-'+value).attr('src', wppaTickImg.src);	// Set icon
	jQuery('#wppa-rate-'+mocc+'-'+value).stop().fadeTo(100, 1.0);		// Fade in fully
	
	// Try to create the http request object
	var xmlhttp = wppaGetXmlHttp();	// This function is in wppa-ajax.js

	if ( wppaRatingUseAjax && xmlhttp ) {								// USE AJAX
		
		// Make the Ajax url
		url = wppaAjaxUrl+'?action=wppa&wppa-action=rate&wppa-rating='+value+'&wppa-rating-id='+photoid;
		url += '&wppa-occur='+mocc+'&wppa-index='+_wppaCurrentIndex[mocc];
		url += '&wppa-nonce='+jQuery('#wppa-nonce').attr('value');
		
		// Setup process the result
		xmlhttp.onreadystatechange=function() 
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				var ArrValues = xmlhttp.responseText.split("||");
				
				if (ArrValues[0] == '0') {	// Error
					alert('Error Code='+ArrValues[1]+'\n\n'+ArrValues[2]);
				}
				else {
					// Store new values
					_wppaPhotoMyRating[ArrValues[0]][ArrValues[2]] = ArrValues[3];
					_wppaPhotoAverages[ArrValues[0]][ArrValues[2]] = ArrValues[4];
					// Update display
					_wppaSetRatingDisplay(mocc);
					jQuery('#wppa-rate-'+mocc+'-'+value).attr('src', wppaTickImg.src);			// Set icon

					if (wppaNextOnCallback) _wppaNextOnCallback(mocc);
				}
			}
		}
		// Do the Ajax action
		xmlhttp.open('GET',url,true);
		xmlhttp.send();	
	}
	else {						// use NON-ajax method, either to setting or browser does not support ajax
		setTimeout('_wppaGo("'+url+'")', 200);	// 200 ms to display tick
	}
}

function _wppaValidateComment(mocc) {
	_wppaLog('ValidateComment', mocc);

	var photoid = _wppaPhotoIds[mocc][_wppaCurrentIndex[mocc]];
	
	// Process name
	var name = jQuery('#wppa-comname-'+mocc).attr('value');
	if (name.length<1) {
		alert(wppaPleaseName);
		return false;
	}
	
	if ( wppaEmailRequired ) {
		// Process email address
		var email = jQuery('#wppa-comemail-'+mocc).attr('value');
		var atpos=email.indexOf("@");
		var dotpos=email.lastIndexOf(".");
		if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length) {
			alert(wppaPleaseEmail);
			return false;
		}
	}
	
	// Process comment
	var text = jQuery('#wppa-comment-'+mocc).attr('value');
	if (text.length<1) {
		alert(wppaPleaseComment);
		return false;
	}
	
	return true;
}

function _wppaGo(url) {
	_wppaLog('Go', 0);
	
	document.location = url;	// Go!
}

function _wppaBbb(mocc,where,act) {
	_wppaLog('Bbb', mocc);
	
	if (_wppaSlideShowRuns[mocc]) return;
	
	var elm = '#bbb-'+mocc+'-'+where;
	switch (act) {
		case 'show':
			jQuery(elm).stop().fadeTo(100, 0.2);
			if (where == 'l') jQuery(elm).attr('title', wppaPreviousPhoto);
			if (where == 'r') jQuery(elm).attr('title', wppaNextPhoto);
			jQuery('.bbb-'+mocc).css('cursor', 'pointer');
			break;
		case 'hide':
			jQuery(elm).stop().fadeTo(400, 0);
			jQuery('.bbb-'+mocc).removeAttr('title');
			jQuery('.bbb-'+mocc).css('cursor', 'default');
			break;
		case 'click':
			if (where == 'l') wppaPrev(mocc);
			if (where == 'r') wppaNext(mocc);
			break;
		default:
			alert('Unimplemented instruction: '+act+' on: '+elm);
	}
}

function _wppaShowMetaData(mocc, key) {
	_wppaLog('ShowMetaData', mocc);
	
	// What to do when the slideshow is NOT running
	if ( ! _wppaSlideShowRuns[mocc] ) {	
		if (key == 'show') {			// Show
			// Show existing comments
			jQuery('#wppa-comtable-wrap-'+mocc).css('display', 'block');
			// Show the input form table
			jQuery('#wppa-comform-wrap-'+mocc).css('display', 'block');
			// Hide the comment footer
			jQuery('#wppa-comfooter-wrap-'+mocc).css('display', 'none');
			// Fade the browse arrows in
			if ( wppaSlideWrap || ( _wppaCurrentIndex[mocc] != 0 ) )
				jQuery('.wppa-prev-'+mocc).fadeIn(300);
			if ( wppaSlideWrap || ( _wppaCurrentIndex[mocc] != (_wppaSlides[mocc].length - 1) ) )
				jQuery('.wppa-next-'+mocc).fadeIn(300);
		}
		else {							// Hide
			// Hide existing comments
			jQuery('#wppa-comtable-wrap-'+mocc).css('display', 'none');
			// Hide the input form table
			jQuery('#wppa-comform-wrap-'+mocc).css('display', 'none');
			// Hide the comment footer
			jQuery('#wppa-comfooter-wrap-'+mocc).css('display', 'block');
			// Fade the browse arrows out
//			jQuery('.wppa-prev-'+mocc).fadeOut(300);	
//			jQuery('.wppa-next-'+mocc).fadeOut(300);
		}
	}
	// What to do when the slideshow is running
	else {	// Slideshow is running
	}
	
	// What to do always, independant of slideshow is running
	if (key == 'show') {
		// Show title and description
		jQuery("#imagedesc-"+mocc).css('visibility', 'visible');
		jQuery("#imagetitle-"+mocc).css('visibility', 'visible');
		// Display counter
		jQuery("#counter-"+mocc).css('visibility', 'visible');
		// Display iptc
		jQuery("#iptccontent-"+mocc).css('visibility', 'visible'); 
		jQuery("#exifcontent-"+mocc).css('visibility', 'visible'); 
	}
	else {
		// Hide title and description
		jQuery("#imagedesc-"+mocc).css('visibility', 'hidden'); 
		jQuery("#imagetitle-"+mocc).css('visibility', 'hidden');
		// Hide counter	
		jQuery("#counter-"+mocc).css('visibility', 'hidden');
		// Fade the browse arrows out
		jQuery('.wppa-prev-'+mocc).fadeOut(300);	
		jQuery('.wppa-next-'+mocc).fadeOut(300);
		// Hide iptc
		jQuery("#iptccontent-"+mocc).css('visibility', 'hidden'); 
		jQuery("#exifcontent-"+mocc).css('visibility', 'hidden'); 
	}
}
var first=true;
function _wppaLog(text, mocc) {
	
	if ( ! document.getElementById('wppa-debug-'+mocc) ) return;	// Debugging off
	var elm = document.getElementById('wppa-debug-'+mocc);
	var old_html = elm.innerHTML;
	var html = '<br>[wppa js] '+mocc+' run=';
	if ( _wppaSlideShowRuns[mocc] ) html += 'yes'; else html += 'no ';
	html += ' busy=';
	if ( _wppaIsBusy[mocc] ) html += 'yes'; else html += 'no ';
	html += ' tp=';
	if ( _wppaTogglePending[mocc] ) html += 'yes'; else html += 'no ';
	html += ' '+text;
	
	html += old_html;
	if ( html.length > 1000 ) html = html.substr(0, 1000);
	elm.innerHTML = html;	// prepend logmessage
}

