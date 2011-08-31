// javascript flash detection
// {{{ jquery.browser.flash
jQuery.extend(jQuery.browser, {
    flash: (function (neededVersion) {
        var found = false;
	var version = "0,0,0";

	try {
	    // get ActiveX Object for Internet Explorer
	    version = new ActiveXObject("ShockwaveFlash.ShockwaveFlash").GetVariable('$version').replace(/\D+/g, ',').match(/^,?(.+),?$/)[1];
	} catch(e) {
	    // check plugins for Firefox, Safari, Opera etc.
	    try {
		if (navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin) {
		    version = (navigator.plugins["Shockwave Flash 2.0"] || navigator.plugins["Shockwave Flash"]).description.replace(/\D+/g, ",").match(/^,?(.+),?$/)[1];
		}
	    } catch(e) {
		return false;
	    }		
	}

	var pv = version.match(/\d+/g);
	var rv = neededVersion.match(/\d+/g);

	for (var i = 0; i < 3; i++) {
	    pv[i] = parseInt(pv[i] || 0);
	    rv[i] = parseInt(rv[i] || 0);

	    if (pv[i] < rv[i]) {
		// player is less than required
	       	return false;
	    } else if (pv[i] > rv[i]) {
		// player is greater than required
		return true;
	    }
	}
	// major version, minor version and revision match exactly
	return true;
    })
});
// }}}
// {{{ jquery.browser.iphone
jQuery.browser.iphone = function() {
    return /iphone/.test(navigator.userAgent.toLowerCase());
}();
// }}}
// {{{ jquery.flash
jQuery.fn.flash = function(params) {
    var html1 = "";
    var html2 = "";
    var flashParam = [];

    for (var p in params.params) {
	flashParam.push(p + "=" + encodeURI(params.params[p]));
    }

    //object part
    html1 += "<object type=\"application/x-shockwave-flash\" ";
    html1 += "data=\"" + params.src + "?" + flashParam.join("&amp;") + "\" ";
    if (params.width !== undefined) {
	html1 += "width=\"" + params.width + "\" ";
    }
    if (params.height !== undefined) {
	html1 += "height=\"" + params.height + "\" ";
    }
    if (params.className !== undefined) {
	html1 += "class=\"" + params.className + "\" ";
    }
    if (params.id !== undefined) {
	html1 += "id=\"" + params.id + "\" ";
    }

    //param part
    html2 += "<param name=\"movie\" value=\"" + params.src + "?" + flashParam.join("&amp;") + "\" />";

    if (params.transparent === true) {
	html1 += "mwmode=\"transparent\"";
	html2 += "<param name=\"wmode\" value=\"transparent\" />";
    }
    html1 += ">";

    return $(html1 + html2 + "</object>");
};
// }}}

(function($){
    var reader_visible = false;

    // {{{ addScrollEvents
    function addScrollEvents() {
        if ($.browser.msie) {
            var scroller = $("body")[0];
        } else {
            var scroller = window;
        }

        $(window).scroll( function() {
            if (!reader_visible) {
                var scrollCorrection = -$(window).scrollTop();

                $("#header").css({
                    top: scrollCorrection + "px"
                });
                $("#footer").css({
                    top: scrollCorrection + 486 + "px"
                });
            }
        });

        $(scroller).mousewheel( function(e, delta) {
            if (!reader_visible) {
                window.scrollTo($(window).scrollLeft() - 50 * delta, $(window).scrollTop());

                return false;
            } else {
                return true;
            }
        });
    }
    // }}}
    // {{{ adjustContentWidth
    function adjustContentWidth() {
        var view = $(window);

        if ($.browser.flash("10,0,0")) {
            var width = 900;
        } else {
            var width = 300;
        }

        if (!reader_visible) {
            if ($(".entry-body").length == 1) {
                var selector = ".entry-body > *";
                var width_correction = 25;
            } else {
                var selector = ".entries > *";
                var width_correction = 15;
            }
            $(selector).each(function() {
                width += $(this).width() + width_correction;
            });
        } else {
            width = 0;
        }
        if (width < view.width()) {
            width = view.width();
        }

        $("#wrap, #header, #footer").width(width);
        $("#reader .content").css({
            minHeight: $(this).height() - 60
        });
    }
    // }}}
    // {{{ addCategoryHover
    function addCategoryHover() {
        $("#footer ul ul .cat-item a").each( function() {
            $(this).append("<span class=\"description\">" + this.title + "</span>");
            $(this).hover(
                function() {
                    $(".description", this).fadeIn();
                }, function () {
                    $(".description", this).hide();
                }
            );
        });
    }
    // }}}
    // {{{ addMindplayerInfo
    function addMindplayerInfo() {
        if ($.browser.flash("10,0,0")) {
            var $mindplayer = $("#mind-player");

            $mindplayer.append("<div class=\"info\"><p class=\"outside\">To find what's related, click around the circle</p><p class=\"inside\">To open the page, click inside the circle<p></div>");
            $mindplayer.hover( function() {
                // hover
                $(".info", $mindplayer).slideDown(200);
            }, function() {
                // out
                $(".info", $mindplayer).slideUp(200);
            });
        }
    }
    // }}}
    // {{{ addIntroScrollLinks
    function addIntroScrollLinks() {
        $(".intro:first").each( function() {
            if ($(this).hasClass("scrollto")) {
                var sign = "&gt;&gt;&gt;&gt;";
            } else {
                var sign = "&raquo";
            }
            $intro = $(this);
            $("<a class=\"scrolltocontent\" href=\"#\">" + sign + "</a>").appendTo(this).click( function() {
                var nextOffset = $intro.next(".entry.teaser").offset();

                $("body,html").animate({
                    scrollLeft: nextOffset.left - 450
                }, 1000);
                

                return false;
            });
        });
    }
    // }}}
    // {{{ addLoginBoxEvents
    function addLoginBoxEvents() {
        var form = $("#sidebarlogin form");
        if (form.length > 0) {
            form.hide();

            $("#sidebarlogintoggle").toggle(
                function() {
                    form.show();
                }, function() {
                    form.hide();
                }
            );
        } else {
            $("#sidebarlogintoggle").hide();
        }
    }
    // }}}
    // reader button
    // {{{ addReaderButton
    function addReaderButton() {
        if (!($.browser.msie && parseInt($.browser.version) < 8)) {
            $("#post-content .entry-body").each( function() {
                var readerbutton = $("<li><a href=\"#\" class=\"read\">read!</a></li>").insertAfter(".top-menu .current_page_item").click( showReader );
            });
            }
    }
    // }}}
    // {{{ showReader
    function showReader() {
        reader_visible = true;

        $("#post-content .entry-body").each( function() {
            var reader = $("<div id=\"reader\"></div>").insertAfter("#content");
            var close = $("<a href=\"#\" class=\"close\">x</a>").appendTo(reader).click( hideReader );
            var content = $("<div class=\"content\"></div>").appendTo(reader);

            $(this).children().clone().appendTo(content);

            $("#content, #main-bottom-menu, .top-menu").hide();
            adjustContentWidth();
            content.hide().fadeIn();
        });

        return false;
    }
    // }}}
    // {{{ hideReader
    function hideReader() {
        reader_visible = false;

        $("#content, #main-bottom-menu, .top-menu").show();
        adjustContentWidth();
        $("#reader").fadeOut( function() {
            $(this).remove();
        });

        return false;
    }
    // }}}

    // {{{ addTeaserEvents
    function addTeaserEvents() {
        $(".teaser").click( function() {
            document.location = $("a", this)[0].href;
        });
    }
    // }}}
    // {{{ addLinkTargets
    function addLinkTargets() {
        var baselink = $("#logo")[0].href;

        $("a").each(function() {
            if (this.href.substr(0, baselink.length) != baselink && this.href.substr(0, 4) == "http") {
                $(this).attr("target", "_blank");
            }
        });
    }
    // }}}
    
    // email anti-spam obfuscation
// {{{ replaceEmailChars()
function replaceEmailChars(mail) {
    mail = unescape(mail);
    mail = mail.replace(/ \*at\* /g, "@");
    mail = mail.replace(/ \*dot\* /g, ".");
    mail = mail.replace(/ \*punkt\* /g, ".");
    mail = mail.replace(/ \*underscore\* /g, "_");
    mail = mail.replace(/ \*unterstrich\* /g, "_");
    mail = mail.replace(/ \*minus\* /g, "-");
    mail = mail.replace(/mailto: /, "mailto:");

    return mail;
}
// }}}
// {{{ replaceEmailRefs()
function replaceEmailRefs() {
    $("a[href*='mailto:']").each(function() {
        // replace attribute
        $(this).attr("href", replaceEmailChars($(this).attr("href")));
        
        //replace content if necessary
        if ($(this).text().indexOf(" *at* ") > 0) {
            $(this).text(replaceEmailChars($(this).text()));
        }
    });
}
// }}}
			
    // {{{ register events
    $(document).ready(function() {
        // add flash content
        if ($.browser.flash("10,0,0")) {
            $("body").addClass("flash");
        }

        addScrollEvents();
        addTeaserEvents();
        addCategoryHover();
        addLoginBoxEvents();
        adjustContentWidth();
        replaceEmailRefs();
        addIntroScrollLinks();
        addLinkTargets();
        addReaderButton();
        addMindplayerInfo();

        $(window).load( function() {
            adjustContentWidth();
        });
        $(window).resize( function() {
            adjustContentWidth();
        });
    });
    // }}}
})(jQuery)

/* vim:set ft=javascript sw=4 sts=4 fdm=marker : */
