/*
======================================================================================
    ||
    * interface.home.js
    * Code by Nicow 2017
    * www.wbxdsg.com
    ||

    || REWORK VERSION BASED ON ORIGINAL
    * interface.js
    * Code by Thomasfds 2021
    * www.thomasfds.fr
    ||
======================================================================================
*/

window.onload = () => {

    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })


    let sub = document.querySelectorAll('.sub');
    let button_menu = document.querySelector('[data-nav]');
    console.log(sub)

    /****** ARMURERIE *****/
    if (document.querySelector('[data-item-id]') != null) {
        document.querySelector('[data-item-id]').addEventListener('click', function() {
            var id = document.querySelector(this).data('item-id');

            document.querySelector('[data-item-id]').removeClass('active');
            document.querySelector(this).classList.add('active');

            document.querySelector('[data-look-id]').hide();
            document.querySelector('[data-look-id="' + id + '"]').show();

        });
    }

    /****** NAV *****/

    if (sub != null) {
        sub.forEach(subs => {
            subs.addEventListener('mouseenter', (e) => {
                console.log(e.target.children)
                let menu = e.target.children[1];
                let nbr = menu.children.length;
                let h = nbr * 42 + 7;
                animate(menu, "height", h + 'px', 200);
            });

            subs.addEventListener('mouseleave', (e) => {
                let menu = e.target.children[1];
                animate(menu, "height", '0px', 200);
            })
        })
    }


    button_menu.addEventListener("click", e => {
        let a = null;
        a = "ico_navicon" === e.target.className ? e.target.parentElement.dataset.nav : e.target.dataset.nav;
        let nav = document.querySelector("nav");
        "no" == a ? (animate(nav, "left", "0px", 200), button_menu.setAttribute("data-nav", "yes")) : (console.log("ici"), animate(nav, "left", "-200px", 200), button_menu.setAttribute("data-nav", "no"))
    })


    function animate(node, prop, end, duration) {
        var stepTime = 20;
        var startTime = new Date().getTime();
        var start = parseInt(getComputedStyle(node).getPropertyValue(prop), 10);
        if (typeof end === "string") {
            end = parseInt(end, 10);
        }

        function step() {
            // calc how much time has elapsed
            var nextValue, done, portionComplete;
            var timeRunning = new Date().getTime() - startTime;
            if (timeRunning >= duration) {
                nextValue = end;
                done = true;
            } else {
                portionComplete = timeRunning / duration;
                nextValue = ((end - start) * portionComplete) + start;
                done = false;
            }
            // set the next value
            node.style[prop] = nextValue + "px";
            if (!done) {
                setTimeout(step, stepTime);
            } else {
                // context = context || window;
                // fn.call(context, node, arg);
            }
        }
        // start the animation
        step();
    }
}