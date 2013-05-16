window.onload = function () {
    var R = Raphael("maps", 300, 300);
    var attr = {
        fill: "#333",
        stroke: "#666",
        "stroke-width": 1,
        "stroke-linejoin": "round"
    };
    var aus = {};
    $('.path').each(function(){
        //aus[$(this).attr('id')] = R.path($(this).attr('d'));
    });
    
    var current = null;
    for (var state in aus) {
        aus[state].color = Raphael.getColor();
        (function (st, state) {
            if(this.est == undefined){this.est = 0;}
            st[0].style.cursor = "pointer";
            st[0].onmouseover = function () {
                if (this.est == 0){
                    current && aus[current].animate({fill: "#333", stroke: "#666"}, 500) && (document.getElementById(current).style.display = "");
                    st.animate({fill: st.color, stroke: "#ccc"}, 500);
                    st.toFront();
                    R.safari();
                    document.getElementById(state).style.display = "block";
                    current = state;
                }
            };

            st[0].onmouseout = function () {
                if (this.est == 0){
                    st.animate({fill: "#333", stroke: "#666"}, 500);
                    st.toFront();
                    R.safari();
                }
            };

            st[0].onclick = function () {
                if (this.est == 0) this.est = 1;
                else this.est = 0;
                st.animate({ fill: "#C05219", stroke: "#E0B6B2" }, 500);
                st.toFront();

                R.safari();
            };

        })(aus[state], state);
    }
};