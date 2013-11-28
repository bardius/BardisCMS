jQuery(document).ready(function($) {

    // Image preview for media/files
    $('.imageLink').on('click', function(event) {
	event.preventDefault();
	var realImage = $(this).attr("href");

	$('#imagePreview').attr('src', realImage);

	$('#imagePreview').load(function() {
	    $('#dialogPreview').dialog({
		modal: true,
		resizable: false,
		draggable: false,
		width: 'auto'
	    });
	});
    });

    // Autocomplete for selectboxes
    if ($('.autoCompleteItems').length > 0)
    {
	$(".autoCompleteItems").combobox();
    }

    // Autocomplete for selectboxes
    if ($('.autoCompleteMenus').length > 0)
    {
	$(".autoCompleteMenus").combobox();
    }

    // Datepicker
    if ($('.datepicker').length > 0)
    {
	$('.datepicker').datepicker({dateFormat: 'dd-mm-yy'});
    }

    // Autogenerate alias form title
    if ($(".pageTitleField").length > 0) {
	$(".pageTitleField").blur(function() {
	    titleVal = $(this).val();
	    titleVal = trim(titleVal);
	    aliasVal = $(".pageAliasField").val();
	    if (aliasVal.length) {
		return false;
	    }

	    $(".pageAliasField").val(titleVal);

	});
    }
    
    // Collapse/expande content blocks on click of the legend    
    $('.sonata-ba-fielset-collapsed .sonata-ba-collapsed').on('click', function(e) {
	e.preventDefault();
	
	var $this = $(this);
	var $collapse = $this.closest('.sonata-ba-fielset-collapsed').find('.sonata-ba-collapsed-fields');
	$collapse.collapse('toggle');
    });

    // Reorder content blocks by dragging them.
    availableContentSlots = new Array('bannercontentblocks', 'maincontentblocks', 'secondarycontentblocks', 'extracontentblocks', 'modalcontentblocks');

    for (availableContentSlot in availableContentSlots)
    {
	if ($("." + availableContentSlots[availableContentSlot]).length > 0)
	{
	    $("." + availableContentSlots[availableContentSlot]).sortable({
		placeholder: "ui-state-highlight"
	    });

	    $("." + availableContentSlots[availableContentSlot]).bind("sortupdate", function(event, ui) {
		var orderedItems = $(this).sortable("toArray");
		for (orderedPosition in orderedItems)
		{
		    orderInputField = getInputField($(this).attr('id'), orderedItems[orderedPosition], 'orderField');
		    $(orderInputField).val(orderedPosition);
		}
	    });
	}
    }

    $("form").on('submit', function(e) {
	$('.orderField').each(function(i, el) {
	    if (!$(el).val())
	    {
		$(el).val(i + 1);
	    }
	});
    });
    
});

// Helper functions
function getInputField(containerId, contentId, fieldbox) {
    var inputField = $("#" + containerId + " #" + contentId + " ." + fieldbox);
    return inputField;
}

function trim(string) {
    string = string.toLowerCase();
    string = string.replace(/[^a-zA-Z0-9]+/g, "-");

    return string;
}

(function($) {
    $.widget("ui.combobox", {
	_create: function() {
	    var input,
		    self = this,
		    select = this.element.hide(),
		    selected = select.children(":selected"),
		    value = selected.val() ? selected.text() : "",
		    wrapper = this.wrapper = $("<span>")
		    .addClass("ui-combobox")
		    .insertAfter(select);

	    input = $("<input>")
		    .appendTo(wrapper)
		    .val(value)
		    .addClass("ui-state-default ui-combobox-input")
		    .autocomplete({
		delay: 0,
		minLength: 0,
		source: function(request, response) {
		    var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
		    response(select.children("option").map(function() {
			var text = $(this).text();
			if (this.value && (!request.term || matcher.test(text)))
			    return {
				label: text.replace(
					new RegExp(
					"(?![^&;]+;)(?!<[^<>]*)(" +
					$.ui.autocomplete.escapeRegex(request.term) +
					")(?![^<>]*>)(?![^&;]+;)", "gi"
					), "<strong>$1</strong>"),
				value: text,
				option: this
			    };
		    }));
		},
		select: function(event, ui) {
		    ui.item.option.selected = true;
		    self._trigger("selected", event, {
			item: ui.item.option
		    });
		},
		change: function(event, ui) {
		    if (!ui.item) {
			var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex($(this).val()) + "$", "i"),
				valid = false;
			select.children("option").each(function() {
			    if ($(this).text().match(matcher)) {
				this.selected = valid = true;
				return false;
			    }
			});
			if (!valid) {
			    // remove invalid value, as it didn't match anything
			    $(this).val("");
			    select.val("");
			    input.data("autocomplete").term = "";
			    return false;
			}
		    }
		}
	    })
		    .addClass("ui-widget ui-widget-content ui-corner-left");

	    input.data("autocomplete")._renderItem = function(ul, item) {
		return $("<li></li>")
			.data("item.autocomplete", item)
			.append("<a>" + item.label + "</a>")
			.appendTo(ul);
	    };

	    $("<a>")
		    .attr("tabIndex", -1)
		    .attr("title", "Show All Items")
		    .appendTo(wrapper)
		    .button({
		icons: {
		    primary: "ui-icon-triangle-1-s"
		},
		text: false
	    })
		    .removeClass("ui-corner-all")
		    .addClass("ui-corner-right ui-combobox-toggle")
		    .click(function() {
		// close if already visible
		if (input.autocomplete("widget").is(":visible")) {
		    input.autocomplete("close");
		    return;
		}

		// work around a bug (likely same cause as #5265)
		$(this).blur();

		// pass empty string as value to search for, displaying all results
		input.autocomplete("search", "");
		input.focus();
	    });
	},
	destroy: function() {
	    this.wrapper.remove();
	    this.element.show();
	    $.Widget.prototype.destroy.call(this);
	}
    });
})(jQuery);