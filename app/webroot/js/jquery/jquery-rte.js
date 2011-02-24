/**
 * jQuery RTE plugin 0.5.1 - create a rich text form for Mozilla, Opera, Safari and Internet Explorer
 *
 * Modifications by Darren Moore for Opencamp 
 * @todo Clean up all JS and fix any bad HTML (e.g. toolbar)
 *
 * Copyright (c) 2009 Batiste Bieler
 * Distributed under the GPL Licenses.
 * Distributed under the MIT License.
 */

// define the rte light plugin
(function($) {

if(typeof $.fn.rte === "undefined") {

    var defaults = {
        media_url: "",
        content_css_url: "rte.css",
        dot_net_button_class: null,
        max_height: 350
    };

    $.fn.rte = function(options) {

    $.fn.rte.html = function(iframe) {
        return iframe.contentWindow.document.getElementsByTagName("body")[0].innerHTML;
    };

    // build main options before element iteration
    var opts = $.extend(defaults, options);

    // iterate and construct the RTEs
    return this.each( function() {
        var textarea = $(this);
        var iframe;
        var element_id = textarea.attr("id");

        // enable design mode
        function enableDesignMode() {

            var content = textarea.val();
            

            // Mozilla needs this to display caret
            if($.trim(content)=='') {
                content = '<div></div>';
            }
            else
            {
                content = content.replace(/\n/g,'<br>');
            }

            // already created? show/hide
            if(iframe) {
                //console.log("already created");
                textarea.hide();
                $(iframe).contents().find("body").html(content);
                $(iframe).show();
                $("#toolbar-" + element_id).remove();
                textarea.before(toolbar());
                textarea.after(footer());
                return true;
            }

            // for compatibility reasons, need to be created this way
            iframe = document.createElement("iframe");
            iframe.frameBorder=0;
            iframe.frameMargin=0;
            iframe.framePadding=0;
            iframe.height=200;
            if(textarea.attr('class'))
                iframe.className = textarea.attr('class');
            if(textarea.attr('id'))
                iframe.id = element_id;
            if(textarea.attr('name'))
                iframe.title = textarea.attr('name');

            textarea.after(iframe);

            var css = "";
            if(opts.content_css_url) {
                css = "<link type='text/css' rel='stylesheet' href='" + opts.content_css_url + "' />";
            }

            var doc = "<html><head>"+css+"</head><body class='frameBody'>"+content+"</body></html>";
            tryEnableDesignMode(doc, function() {
                changeFormat('richtext');
                $("#toolbar-" + element_id).remove();
                textarea.before(toolbar());
                textarea.after(footer());
                // hide textarea
                textarea.hide();
            });

        }

        function tryEnableDesignMode(doc, callback) {
            if(!iframe) { return false; }

            try {
                iframe.contentWindow.document.open();
                iframe.contentWindow.document.write(doc);
                iframe.contentWindow.document.close();
            } catch(error) {
                //console.log(error);
            }
            if (document.contentEditable) {
                iframe.contentWindow.document.designMode = "On";
                callback();
                return true;
            }
            else if (document.designMode != null) {
                try {
                    iframe.contentWindow.document.designMode = "on";
                    callback();
                    return true;
                } catch (error) {
                    //console.log(error);
                }
            }
            setTimeout(function(){tryEnableDesignMode(doc, callback)}, 500);
            return false;
        }
        
        
        function changeFormat(format)
        {
            var formatInput = $(textarea).closest('form').find('input[id*=Format]');
            formatInput.val(format);
        }
        

        function disableDesignMode(submit) {
            var content = $(iframe).contents().find("body").html();
            
            content = toTextile(content);

            if($(iframe).is(":visible")) {
                textarea.val(content);
            }

            if(submit !== true) {
                textarea.show();
                $(iframe).hide();
                changeFormat('textile');
            }
        }
        
        
        function toRichtext(content)
        {
            return content;
        }
        
        
        function toTextile(content)
        {
            content = content.replace(/<\/div>/ig,"\n");
            content = content.replace(/<\/li>/ig,"\n");
            content = content.replace(/<\/ul>/ig,"\n");
            content = content.replace(/<\/ol>/ig,"\n");
            content = content.replace(/<br>/ig,"\n");
            
            var tmp = document.createElement("DIV");
            tmp.innerHTML = content;
            content = tmp.textContent||tmp.innerText;
            
            return content;
        }
        

        // create toolbar and bind events to it's elements
        function toolbar() {
            var tb = $("<div class='rte-toolbar' id='toolbar-"+ element_id +"'><div>\
                <p>\
                    <a href='#' class='bold' rel-tag='b'>Bold</a>\
                    <a href='#' class='italic' rel-tag='i'>Italic</a>\
                    <a href='#' class='unorderedlist' rel-tag='ul'>Bullets</a>\
                    <a href='#' class='orderedlist' rel-tag='ol'>Numbers</a>\
                </p></div></div>");

            $('.bold', tb).click(function(){ formatText('bold','bold');return false; });
            $('.italic', tb).click(function(){ formatText('italic','italic');return false; });
            $('.unorderedlist', tb).click(function(){ formatText('insertunorderedlist','unorderedlist');return false; });
            $('.orderedlist', tb).click(function(){ formatText('insertorderedlist','orderedlist');return false; });

            // .NET compatability
            if(opts.dot_net_button_class) {
                var dot_net_button = $(iframe).parents('form').find(opts.dot_net_button_class);
                dot_net_button.click(function() {
                    disableDesignMode(true);
                });
            // Regular forms
            } else {
                $(iframe).parents('form').submit(function(){
                    disableDesignMode(true);
                });
            }

            var iframeDoc = $(iframe.contentWindow.document);
            
            iframeDoc.mouseup(function(){
                setActiveType(getSelectionElement());
                return true;
            });

            iframeDoc.keyup(function() {
                setActiveType(getSelectionElement());
                
                var body = $('body', iframeDoc);
                if(body.scrollTop() > 0) {
                    var iframe_height = parseInt(iframe.style['height'])
                    if(isNaN(iframe_height))
                        iframe_height = 0;
                    var h = Math.min(opts.max_height, iframe_height+body.scrollTop()) + 'px';
                    iframe.style['height'] = h;
                }
                return true;
            });

            return tb;
        };
        
        

        // create footer
        function footer() {
            var tb = $("<p class='format-options'><a href='#' class='format-switch unimportant'>Switch to Textile/HTML</a></p>");
            //var tb = $('<div class="format-switch">Switch to Textile/HTML</div>');

            $('.format-switch', tb).click(function() {
                disableDesignMode();
                $('.rte-toolbar').remove();
                var edm = $('<a href="#" class="format-switch unimportant">Go back to easy formatting</a>');
                tb.empty().append(edm);
                edm.click(function(e){
                    e.preventDefault();
                    enableDesignMode();
                    // remove, for good measure
                    $(this).remove();
                    return false;
                });
                return false;
            });

            return tb;
        };
        

        function formatText(command, option, dom) {
            iframe.contentWindow.focus();
            
            try{
                iframe.contentWindow.document.execCommand(command, false, option);
            }catch(e){
                //console.log(e)
            }
            iframe.contentWindow.focus();
        };


        function setActiveType(node, select)
        {
            $('#toolbar-'+element_id+' a').removeClass('active');
        
            while(node.parentNode)
            {
                var nName = node.nodeName.toLowerCase();
                $('#toolbar-'+element_id+' a[rel-tag='+nName+']').addClass('active');
                
                node = node.parentNode;
            }
            
            return true;
        };


        function getSelectionElement() {
            if (iframe.contentWindow.document.selection) {
                // IE selections
                selection = iframe.contentWindow.document.selection;
                range = selection.createRange();
                try {
                    node = range.parentElement();
                }
                catch (e) {
                    return false;
                }
            } else {
                // Mozilla selections
                try {
                    selection = iframe.contentWindow.getSelection();
                    range = selection.getRangeAt(0);
                }
                catch(e){
                    return false;
                }
                node = range.commonAncestorContainer;
            }
            return node;
        };
        
        // enable design mode now
        enableDesignMode();

    }); //return this.each
    
    }; // rte

} // if

})(jQuery);
