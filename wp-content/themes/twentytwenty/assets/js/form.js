(() => {
    function a(d) {
        if (c[d]) return c[d].exports;
        var e = (c[d] = { exports: {} });
        return b[d](e, e.exports, a), e.exports;
    }
    var b = {
            604: () => {
                var a = document.querySelectorAll(".deon-item__title");
                if (0 < a.length)
                    for (var b = 0; b < a.length; b++)
                        a[b].addEventListener("click", function (a) {
                            var b = a.target;
                            b.classList.contains("deon-item__title") || (b = b.parentElement),
                                b.classList.contains("is-open") ? (b.classList.remove("is-open"), (b.nextElementSibling.style.display = "none")) : (b.classList.add("is-open"), (b.nextElementSibling.style.display = "block"));
                        });
            },
            676: () => {
                
            },
            877: () => {
                window.addComment = (function (v) {
                    function a() {
                        w(), b();
                    }
                    function w(f) {
                        if (p && ((l = z(q.cancelReplyId)), (m = z(q.commentFormId)), l)) {
                            l.addEventListener("touchstart", d), l.addEventListener("click", d);
                            for (var a, b = x(f), i = 0, g = b.length; i < g; i++) (a = b[i]), a.addEventListener("touchstart", e), a.addEventListener("click", e);
                        }
                    }
                    function x(d) {
                        var a,
                            b = q.commentReplyClass;
                        return (d && d.childNodes) || (d = o), (a = o.getElementsByClassName ? d.getElementsByClassName(b) : d.querySelectorAll("." + b));
                    }
                    function d(e) {
                        var a = this,
                            b = q.temporaryFormId,
                            c = z(b);
                        c && n && ((z(q.parentIdFieldId).value = "0"), c.parentNode.replaceChild(n, c), (a.style.display = "none"), e.preventDefault());
                    }
                    function e(a) {
                        var b,
                            c = this,
                            d = y(c, "belowelement"),
                            e = y(c, "commentid"),
                            i = y(c, "respondelement"),
                            g = y(c, "postid");
                        d && e && i && g && ((b = v.addComment.moveForm(d, e, i, g)), !1 === b && a.preventDefault());
                    }
                    function b() {
                        if (i) {
                            (h = new i(f)), h.observe(o.body, { childList: !0, subTree: !0 });
                        }
                    }
                    function f(c) {
                        for (var a = c.length; a--; ) if (c[a].addedNodes.length) return void w();
                    }
                    function y(c, a) {
                        return r ? c.dataset[a] : c.getAttribute("data-" + a);
                    }
                    function z(b) {
                        return o.getElementById(b);
                    }
                    function g(a, b, c, d) {
                        var e = z(a);
                        n = z(c);
                        var f,
                            g,
                            r,
                            i = z(q.parentIdFieldId),
                            t = z(q.postIdFieldId);
                        if (e && n && i) {
                            j(n),
                                d && t && (t.value = d),
                                (i.value = b),
                                (l.style.display = ""),
                                e.parentNode.insertBefore(n, e.nextSibling),
                                (l.onclick = function () {
                                    return !1;
                                });
                            try {
                                for (var p = 0; p < m.elements.length; p++)
                                    if (
                                        ((f = m.elements[p]),
                                        (g = !1),
                                        "getComputedStyle" in v ? (r = v.getComputedStyle(f)) : o.documentElement.currentStyle && (r = f.currentStyle),
                                        ((0 >= f.offsetWidth && 0 >= f.offsetHeight) || "hidden" === r.visibility) && (g = !0),
                                        "hidden" !== f.type && !f.disabled && !g)
                                    ) {
                                        f.focus();
                                        break;
                                    }
                            } catch (a) {}
                            return !1;
                        }
                    }
                    function j(d) {
                        var a = q.temporaryFormId,
                            b = z(a);
                        b || ((b = o.createElement("div")), (b.id = a), (b.style.display = "none"), d.parentNode.insertBefore(b, d));
                    }
                    var l,
                        m,
                        n,
                        h,
                        o = v.document,
                        q = {
                            commentReplyClass: "comment-reply-link",
                            cancelReplyId: "cancel-comment-reply-link",
                            commentFormId: "commentform",
                            temporaryFormId: "wp-temp-form-div",
                            parentIdFieldId: "comment_parent",
                            postIdFieldId: "comment_post_ID",
                        },
                        i = v.MutationObserver || v.WebKitMutationObserver || v.MozMutationObserver,
                        p = "querySelector" in o && "addEventListener" in v,
                        r = !!o.documentElement.dataset;
                    return p && "loading" !== o.readyState ? a() : p && v.addEventListener("DOMContentLoaded", a, !1), { init: w, moveForm: g };
                })(window);
            },
            24: () => {
                var a = document.getElementById("sign-in-with-pass");
                a &&
                    a.addEventListener("click", function (a) {
                        var b = a.target;
                        b.classList.contains("yes-with-pass")
                            ? ((document.getElementById("password-field").style.display = "none"),
                              b.classList.remove("yes-with-pass"),
                              (b.textContent = "Sign in with password"),
                              (b.parentElement.previousElementSibling.textContent = "Continue"),
                              (b.parentElement.previousElementSibling.previousElementSibling.value = "auth1"))
                            : (document.getElementById("password-field").classList.remove("error"),
                              (document.getElementById("password-field").style.display = "block"),
                              b.classList.add("yes-with-pass"),
                              (b.textContent = "Sign in with email"),
                              (b.parentElement.previousElementSibling.textContent = "Sign In"),
                              (b.parentElement.previousElementSibling.previousElementSibling.value = "auth2")),
                            a.preventDefault();
                    });
            },
            892: () => {
                var a = document.getElementById("search_button"),
                    b = document.getElementById("search");
                a &&
                    (a.addEventListener("click", function (a) {
                        b.classList.add("is-open"),
                            setTimeout(function () {
                                document.getElementById("s").focus();
                            }, 300),
                            a.preventDefault();
                    }),
                    document.addEventListener("click", function (a) {
                        for (var c = !1, d = a.target; d != document.body; d = d.parentNode)
                            if ("header" == d.id) {
                                c = !0;
                                break;
                            }
                        c || b.classList.remove("is-open");
                    }));
            },
            933: () => {
                var a = document.getElementById("generate-sql");
                a &&
                    a.addEventListener("submit", function (b) {
                        var c = a.querySelectorAll('input[type="text"]');
                        if (
                            (Array.from(c).some(function (a) {
                                return (a.classList.add("validated"), !a.value)
                                    ? (a.parentElement.classList.add("error"), a.focus(), !0)
                                    : 2 > a.value.length
                                    ? (a.parentElement.classList.add("error"), a.focus(), !0)
                                    : void a.parentElement.classList.remove("error");
                            }),
                            0 < a.querySelectorAll(".error").length)
                        )
                            b.preventDefault();
                        else {
                            var d = document.getElementById("tooolddomain").value.replace(/[^a-zA-Z0-9-_/.:]/g, ""),
                                e = document.getElementById("toolnewdomain").value.replace(/[^a-zA-Z0-9-_/.:]/g, ""),
                                f = document.getElementById("toolprefix").value.replace(/[^a-zA-Z0-9-_]/g, "");
                            (document.querySelector(".sql-tool__result").style.display = "block"),
                                (document.querySelector(".copy-link").style.display = "flex"),
                                (document.querySelector("#sql-result").value =
                                    "UPDATE " +
                                    f +
                                    "options SET option_value = REPLACE(option_value, '" +
                                    d +
                                    "', '" +
                                    e +
                                    "') WHERE option_name = 'home' OR option_name = 'siteurl';\nUPDATE " +
                                    f +
                                    "posts SET post_content = REPLACE (post_content, '" +
                                    d +
                                    "', '" +
                                    e +
                                    "');\nUPDATE " +
                                    f +
                                    "posts SET post_excerpt = REPLACE (post_excerpt, '" +
                                    d +
                                    "', '" +
                                    e +
                                    "');\nUPDATE " +
                                    f +
                                    "postmeta SET meta_value = REPLACE (meta_value, '" +
                                    d +
                                    "','" +
                                    e +
                                    "');\nUPDATE " +
                                    f +
                                    "termmeta SET meta_value = REPLACE (meta_value, '" +
                                    d +
                                    "','" +
                                    e +
                                    "');\nUPDATE " +
                                    f +
                                    "comments SET comment_content = REPLACE (comment_content, '" +
                                    d +
                                    "', '" +
                                    e +
                                    "');\nUPDATE " +
                                    f +
                                    "comments SET comment_author_url = REPLACE (comment_author_url, '" +
                                    d +
                                    "','" +
                                    e +
                                    "');\nUPDATE " +
                                    f +
                                    "posts SET guid = REPLACE (guid, '" +
                                    d +
                                    "', '" +
                                    e +
                                    "') WHERE post_type = 'attachment';\n"),
                                document.querySelector("#sql-result").select();
                        }
                        return b.preventDefault(), !1;
                    });
                var b = document.querySelector(".copy-link");
                b &&
                    b.addEventListener("click", function () {
                        var a = b.querySelector("span"),
                            c = document.getElementById("sql-result");
                        (a.textContent = "Copied"),
                            setTimeout(function () {
                                a.textContent = "Copy to clipboard";
                            }, 1e3),
                            c.select(),
                            c.setSelectionRange(0, 99999),
                            document.execCommand("copy");
                    });
            },
            537: () => {
                function a(a) {
                    return e(a) || d(a) || c(a) || b();
                }
                function b() {
                    throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
                }
                function c(a, b) {
                    if (a) {
                        if ("string" == typeof a) return f(a, b);
                        var c = Object.prototype.toString.call(a).slice(8, -1);
                        return "Object" === c && a.constructor && (c = a.constructor.name), "Map" === c || "Set" === c ? Array.from(a) : "Arguments" === c || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(c) ? f(a, b) : void 0;
                    }
                }
                function d(a) {
                    if ("undefined" != typeof Symbol && Symbol.iterator in Object(a)) return Array.from(a);
                }
                function e(a) {
                    if (Array.isArray(a)) return f(a);
                }
                function f(a, b) {
                    (null == b || b > a.length) && (b = a.length);
                    for (var c = 0, d = Array(b); c < b; c++) d[c] = a[c];
                    return d;
                }
                (function (a) {
                    document.addEventListener("DOMContentLoaded", a, !1);
                })(function () {
                    var b = {
                        container: document.querySelector(".tt"),
                        links: null,
                        headings: null,
                        intersectionOptions: { rootMargin: "0px", threshold: 1 },
                        previousSection: null,
                        observer: null,
                        init: function () {
                            var a = this;
                            (this.handleObserver = this.handleObserver.bind(this)),
                                this.setUpObserver(),
                                this.findLinksAndHeadings(),
                                this.observeSections(),
                                this.links.forEach(function (b) {
                                    b.addEventListener("click", a.handleLinkClick.bind(a));
                                });
                        },
                        handleLinkClick: function (a) {
                            a.preventDefault();
                            var b = a.target.getAttribute("href");
                            document.querySelector(b).scrollIntoView({ behavior: "smooth" }), history.replaceState(null, null, document.location.pathname + b);
                        },
                        handleObserver: function (a) {
                            var b = this;
                            a.forEach(function (a) {
                                var c = "#".concat(a.target.id),
                                    d = b.links.find(function (a) {
                                        return a.getAttribute("href") === c;
                                    });
                                a.isIntersecting && 1 <= a.intersectionRatio ? d.parentElement.classList.add("is-visible") : d.parentElement.classList.remove("is-visible"), b.highlightFirstActive();
                            });
                        },
                        highlightFirstActive: function () {
                            var a = this.container.querySelector(".is-visible");
                            (a || this.previousSection) &&
                                (this.links.forEach(function (a) {
                                    a.parentElement.classList.remove("is-active");
                                }),
                                a && ((this.previousSection = a.id), a.classList.add("is-active")),
                                !a && this.previousSection && this.container.querySelector('a[href="#'.concat(this.previousSection, '"]')).parentElement.classList.add("is-active"));
                        },
                        observeSections: function () {
                            var a = this;
                            this.headings.forEach(function (b) {
                                a.observer.observe(b);
                            });
                        },
                        setUpObserver: function () {
                            this.observer = new IntersectionObserver(this.handleObserver, this.intersectionOptions);
                        },
                        findLinksAndHeadings: function () {
                            (this.links = a(this.container.querySelectorAll("a"))),
                                (this.headings = this.links.map(function (a) {
                                    var b = a.getAttribute("href");
                                    return document.querySelector(b);
                                }));
                        },
                    };
                    0 < document.querySelectorAll(".tt").length && b.init();
                    var c = {
                        container: document.querySelector(".tt--plugins"),
                        links: null,
                        sections: null,
                        intersectionOptions: { rootMargin: "-30%", threshold: 0 },
                        previousSection: null,
                        observer: null,
                        init: function () {
                            (this.handleObserver = this.handleObserver.bind(this)), this.setUpObserver(), this.findLinksAndHeadings(), this.observeSections();
                        },
                        handleObserver: function (a) {
                            var b = this;
                            a.forEach(function (a) {
                                var c = "#".concat(a.target.id),
                                    d = b.links.find(function (a) {
                                        return a.getAttribute("href") === c;
                                    });
                                a.isIntersecting &&
                                    (b.links.forEach(function (a) {
                                        a.parentElement.classList.remove("is-active");
                                    }),
                                    d.parentElement.classList.add("is-active"));
                            });
                        },
                        observeSections: function () {
                            var a = this;
                            this.sections.forEach(function (b) {
                                a.observer.observe(b);
                            });
                        },
                        setUpObserver: function () {
                            this.observer = new IntersectionObserver(this.handleObserver, this.intersectionOptions);
                        },
                        findLinksAndHeadings: function () {
                            (this.links = a(this.container.querySelectorAll("a"))),
                                (this.sections = this.links.map(function (a) {
                                    var b = a.getAttribute("href");
                                    return document.querySelector(b);
                                }));
                        },
                    };
                    0 < document.querySelectorAll(".tt--plugins").length && c.init();
                });
                var g = document.querySelectorAll("#post h2[id], #post h3[id]");
                if (g)
                    for (var h = 0; h < g.length; h++)
                        g[h].innerHTML = g[h].textContent + '&nbsp;<a href="#' + g[h].id + '" class="anchor"><svg aria-hidden="true" viewBox="0 0 16 16" width="16" height="16"><use xlink:href="#icon-anchor"></use></svg></a>';
            },
            732: () => {
                var a = document.querySelectorAll("[data-index]");
                if (0 < a.length)
                    for (var b = 0; b < a.length; b++)
                        a[b].addEventListener("click", function (a) {
                            a.preventDefault(), console.log(a);
                            for (var c = a.target; !c.hasAttribute("data-tabs"); ) c = c.parentElement;
                            var d = c.dataset.index,
                                e = c.classList.contains("el-active"),
                                f = document.getElementsByClassName(c.dataset.tabs),
                                g = document.querySelector("." + c.dataset.tabs + '[tab-index="' + d + '"]');
                            if (!e) {
                                var h = c.parentElement.querySelectorAll("[data-index]");
                                for (b = 0; b < h.length; b++) h[b].classList.remove("el-active");
                                for (c.classList.add("el-active"), b = 0; b < f.length; b++) f[b].classList.remove("tab-active");
                                g.classList.add("tab-active");
                            }
                        });
            },
            296: () => {
                (function (a) {
                    var b = a.querySelectorAll(".file");
                    Array.prototype.forEach.call(b, function (a) {
                        var b = a.nextElementSibling,
                            c = b.innerHTML;
                        a.addEventListener("change", function (a) {
                            var d = "";
                            (d = this.files && 1 < this.files.length ? (this.getAttribute("data-multiple-caption") || "").replace("{count}", this.files.length) : a.target.value.split("\\").pop()),
                                d ? (b.querySelector("span").innerHTML = d) : (b.innerHTML = c);
                        }),
                            a.addEventListener("focus", function () {
                                a.classList.add("has-focus");
                            }),
                            a.addEventListener("blur", function () {
                                a.classList.remove("has-focus");
                            });
                    });
                })(document, window, 0);
            },
        },
        c = {};
    (() => {
        "use strict";
        function b(a) {
            "@babel/helpers - typeof";
            return (
                (b =
                    "function" == typeof Symbol && "symbol" == typeof Symbol.iterator
                        ? function (a) {
                              return typeof a;
                          }
                        : function (a) {
                              return a && "function" == typeof Symbol && a.constructor === Symbol && a !== Symbol.prototype ? "symbol" : typeof a;
                          }),
                b(a)
            );
        }
        function c(a) {
            "function" == typeof jQuery && a instanceof jQuery && (a = a[0]);
            var b = a.getBoundingClientRect();
            return 0 <= b.top && 0 <= b.left && b.bottom <= (window.innerHeight || document.documentElement.clientHeight) && b.right <= (window.innerWidth || document.documentElement.clientWidth);
        }
        function d(a) {
            var b = a.parentElement;
            c(b) || b.scrollIntoView({ behavior: "smooth" });
        }
        function e(a) {
            var b = a.parentElement,
                c = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i,
                e = a.value;
            return "email" == a.type
                ? "" == e
                    ? (b.classList.add("error"), (a.nextElementSibling.textContent = "Please, fill in your email."), d(a), !1)
                    : c.test(e)
                    ? (b.classList.remove("error"), !0)
                    : (b.classList.add("error"), (a.nextElementSibling.textContent = "Incorrect email."), d(a), !1)
                : e
                ? 2 > e.length
                    ? (b.classList.add("error"), d(a), !1)
                    : (b.classList.remove("error"), !0)
                : (b.classList.add("error"), d(a), !1);
        }
        var f = a(877),
            g = a(537);
        var h = document.querySelector(".form");
        null !== h &&
            h.addEventListener("submit", function (a) {
                a.preventDefault();
                var b = Array.from(a.target.querySelectorAll(".required"));
                b.some(function (a) {
                    if ("none" !== window.getComputedStyle(a).display && "none" !== window.getComputedStyle(a.parentElement).display && (a.classList.add("validated"), !e(a))) return !0;
                }),
                    a.target.querySelectorAll(".error").length || h.submit();
            });
        var j = document.querySelectorAll(".required");
        if (0 < j.length)
            for (var k = 0; k < j.length; k++)
                j[k].addEventListener("focus", function (a) {
                    a.target.parentElement.classList.remove("error");
                });
        var i = a(892),
            l = a(296),
            n = a(604),
            o = a(732),
            p = a(24),
            q = a(933);
    })();
})();
