/**
 *
 * iPhone/Mobile Javascript Orientation
 * This was written to help web developers detect the orientation of a mobile device when the first iPhone came out.
 * @author Jay Fortner for Mindcomet, 2007-2008
 * 
**/

addEventListener("load", function()
{
    setTimeout(updateLayout, 0);
}, false);

var currentWidth = 0;

function updateLayout()
{
    if (window.innerWidth != currentWidth)
    {
        currentWidth = window.innerWidth;

        var orient = currentWidth == 320 ? "profile" : "landscape";
        document.body.setAttribute("orient", orient);
        setTimeout(function()
        {
            window.scrollTo(0, 1);
        }, 100);           
    }
}

setInterval(updateLayout, 400);