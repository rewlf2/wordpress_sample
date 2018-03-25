jQuery(function($){

	function cmiImport(importOpts){
		$.post(ajaxurl, importOpts, function(response){
			if(response.html != ""){
				$("#cmi-import-results").html(response.html);
			}
			if(!response.finished && response.status){
				//cmiImport(importOpts); We don´t need to pass again importOptions as it is retrieved from import-progress temp file
				cmiImport("action=cmi_import");
			}else{
				$("#cmi-import-btn .cmi-loader").css({"display": "none"});
				$("#cmi-import-btn").prop("disabled", false);
				$("#cmi-import-btn").css({"display": "none"});
			}
		}, "json")
		.always(function(response){
			//console.log(response);
		});
	}

	// Export
	$("#cmi-export-type").change(function(){
		$("#cmi-getfields-wrapper").html("");
		$("#cmi-getfields-wrapper").css("display", "none");
	});
	$("#cmi-getfields-btn").click(function(event){
		event.preventDefault();
		$("#cmi-getfields-btn .cmi-loader").css({"display":"inline-block"});
		$(this).prop("disabled", true);
        var data = {"action":"cmi_get_fields"}
        if($("#cmi-export-type").length){
            data.cmi_export_type = $("#cmi-export-type").val();
        }
		$.post(ajaxurl, data, function(response){
			if(response.html){
                $("#cmi-getfields-wrapper").html(response.html);
				$("#cmi-getfields-wrapper").css("display", "block");
			}
			$("#cmi-getfields-btn .cmi-loader").css({"display":"none"});
			$("#cmi-getfields-btn").prop("disabled", false);
		}, "json");
	});

	var exportBtn = $("#cmi-export-btn");
	var exportBtnLoader = $("#cmi-export-btn .cmi-loader");
    exportBtn.click(function(event){
		event.preventDefault();
		exportBtnLoader.css({"display":"inline-block"});
		exportBtn.prop("disabled", true);
		$.post(ajaxurl, $("#cmi-export-options").serialize(), function(response){
			if(response.exportDest == "download" && response.downloadReady){
				window.open(ajaxurl+"?action=cmi_export_download", "_blank", "width=250, height=250, menubar=no, location=no");
			}
			$("#cmi-export-results").html(response.html);

			exportBtnLoader.css({"display":"none"});
			exportBtn.prop("disabled", false);
		}, "json")
		.always(function(response){
			console.log(response);
		});
	});

	// Import
	$("#cmi-import-source").change(function(){
		$("#cmi-import-options .import-input").addClass("hidden");
		$("#cmi-import-"+$(this).val()).removeClass("hidden");
	});
	$("#cmi-preimport-btn").click(function(event){
		if($("#cmi-import-source").val() != "upload"){
			event.preventDefault();
		}
		$("#cmi-preimport-btn .cmi-loader").css({"display":"inline-block"});
		importOpts = $("#cmi-import-options").serialize();
		importOpts += "&action=cmi_preimport";
		$.post(ajaxurl, importOpts, function(response){
			if(response.html != ""){
				$("#cmi-import-results").html(response.html);
				if(!response.status){
					$("#cmi-import-btn").addClass("hidden");
				}
			}
			if(response.status){
				$("#cmi-import-btn").removeClass("hidden");
			}
			$("#cmi-preimport-btn .cmi-loader").css({"display":"none"});
		}, "json");
	});
	$("#cmi-import-btn").click(function(){
		$("#cmi-import-btn .cmi-loader").css({"display":"inline-block"});
		$(this).prop("disabled", true);
		importOpts  = $("#cmi-import-options").serialize();
		importOpts += "&action=cmi_import";
		cmiImport(importOpts);
	});

	// Date picker
	$.datepicker.setDefaults({
		"dateFormat": "yy/mm/dd"
	});
	$("#cmi-export-date-from").datepicker({
		onSelect: function(selected){
			$("#cmi-export-date-to").datepicker("option", "minDate", selected)
		}
	});
	$("#cmi-export-date-to").datepicker({
		onSelect: function(selected){
			$("#cmi-export-date-from").datepicker("option", "maxDate", selected)
		}
	});

    // Select all fields
	$("#cmi-getfields-wrapper").delegate(".sel-all", "click", function(){
        $("#cmi-getfields-wrapper ."+$(this).data("field-group")).prop("checked", true);
	});

    // Deselect all fields
	$("#cmi-getfields-wrapper").delegate(".des-all", "click", function(){
        $("#cmi-getfields-wrapper ."+$(this).data("field-group")).prop("checked", false);
	});
});
