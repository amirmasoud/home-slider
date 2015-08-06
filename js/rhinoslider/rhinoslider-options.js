jQuery(document).ready(function($){
  "use strict";
      $('#home-slider').rhinoslider({
            effect:     setting.effect,
            easing:     setting.easing,
            effectTime: setting.effectTime,
            showTime:   setting.showTime,
            animateActive: setting.animateActive,
            partDelay: setting.partDelay,
            parts: setting.parts,
            shiftValue: setting.shiftValue,
            slideNextDirection: setting.slideNextDirection,
            slidePrevDirection: setting.slidePrevDirection,
            changeBullets: setting.changeBullets,
            controlFadeTime: setting.controlFadeTime,
            controlsKeyboard: setting.controlsKeyboard,
            controlsMousewheel: setting.controlsMousewheel,
            controlsPlayPause: setting.controlsPlayPause,
            controlsPrevNext: setting.controlsPrevNext,
            nextText: setting.nextText,
            pauseText: setting.pauseText,
            playText: setting.playText,
            prevText: setting.prevText,
            showBullets: setting.showBullets,
            showControls: setting.showControls,
            autoPlay: setting.autoPlay,
            cycled: setting.cycled,
            pauseOnHover: setting.pauseOnHover,
            randomOrder: setting.randomOrder,
            captionsFadeTime: setting.captionsFadeTime,
            captionsOpacity: setting.captionsOpacity,
            showCaptions: setting.showCaptions
      });
});