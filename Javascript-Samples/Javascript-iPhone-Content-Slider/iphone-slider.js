/**
 *
 * iPhone Javascript Slider
 * This was one of the first javascript sliders for the iPhone, this was recognized by the YUI Developer Blog, which paved the way for sliders that you see today.
 * @author Jay Fortner for Mindcomet, 2007-2008
 *
 * Depends on:
 * Yahoo User Interface Library
 * yui/build/yahoo/yahoo.js
 * yui/build/event/event.js
 * yui/build/dom/dom.js
 * yui/build/animation/animation-min.js
 * 
**/


// Animate the Movement of Content
// @usage slide('moveThis', 'target', 'x', 'y');
var movethis,target,x,y,goodToGo=true;

var waitForIt = function() {
	var onTheMove = this.isAnimated();
	if (onTheMove == true) {
		goodToGo=false;
	} else {
		goodToGo=true;
	}
}

function slide(moveThis, target, x, y, whichInstance, action) {
	if (goodToGo == true) {
		if (counter(whichInstance, action) == true) {
			moveThis = document.getElementById(moveThis);
			if (target != null) {
				target = document.getElementById(target);
				var anim = new YAHOO.util.Motion(moveThis, { points: { to: YAHOO.util.Dom.getXY(target) } });
			} else if (x != null && y != null) {
				var anim = new YAHOO.util.Motion(moveThis, { points: { by: [x, y] } });
			}
			anim.onTween.subscribe(waitForIt);
			anim.onComplete.subscribe(waitForIt);
			anim.animate();
		}
	}
}

// A counter for the slide effect
// @usage counter('instance', 'action');
var action, hitormiss;
var instance = new Array('cast', 'episodes');
instance['cast'] = Array('0', '4');
instance['episodes'] = Array('0', '13');

function counter(whichInstance, action) {
	var hitormiss = instance[whichInstance];
	var counter = hitormiss[0];
	var max = hitormiss[1];

	if (action == '+' && counter < max) {
		instance[whichInstance][0]++;
		return true;
	} else if (action == '-' && counter > 0) {
		instance[whichInstance][0]--;
		return true;
	} else {
		return false;
	}
}