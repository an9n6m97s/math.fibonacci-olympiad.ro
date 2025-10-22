<?php
$admin = new Admin($conn);
$adminId = $userData['id'];
?>


<form class="row g-3 needs-validation" novalidate id="admin_changelog_create_form">
    <div class="col-md-3" style="z-index: 1099;">
        <label class="form-label" for="cl_title">Title</label>
        <input class="form-control" id="cl_title" name="title" type="text" placeholder="e.g. ScoreEngine v2.1 hotfix" required />
        <div class="valid-feedback">Looks good.</div>
        <div class="invalid-feedback">Please enter a title.</div>
    </div>

    <div class="col-md-3" style="z-index: 1099;">
        <label class="form-label" for="cl_area">Area</label>
        <select class="form-select" id="cl_area" data-choices="data-choices" size="1" required name="area" data-options='{"removeItemButton":true,"placeholder":true}'>
            <option value="" disabled selected>Select area...</option>
            <option value="website">website</option>
            <option value="ucp">ucp</option>
            <option value="registration">registration</option>
            <option value="scoring">scoring</option>
            <option value="security">security</option>
            <option value="infrastructure">infrastructure</option>
        </select>
        <div class="valid-feedback">Area selected.</div>
        <div class="invalid-feedback">Choose an area.</div>
    </div>

    <div class="col-md-3" style="z-index: 1099;">
        <label class="form-label" for="cl_version">Version</label>
        <input class="form-control" id="cl_version" name="version" type="text" placeholder="e.g. 2.1.0" />
        <div class="valid-feedback">Version noted.</div>
        <div class="invalid-feedback">Please provide a version (optional, but keep format sane).</div>
    </div>

    <div class="col-md-3" style="z-index: 1099;">
        <label class="form-label" for="cl_visible_to">Visible To</label>
        <select class="form-select" id="cl_visible_to" data-choices="data-choices" size="1" required name="visible_to" data-options='{"removeItemButton":true,"placeholder":true}'>
            <option value="" disabled selected>Select audience...</option>
            <option value="all">all</option>
            <option value="admin">admin</option>
        </select>
        <div class="valid-feedback">Audience set.</div>
        <div class="invalid-feedback">Select who can see this.</div>
    </div>

    <div class="col-md-3" style="z-index: 1098;">
        <label class="form-label" for="cl_status">Status</label>
        <select class="form-select" id="cl_status" data-choices="data-choices" size="1" required name="status" data-options='{"removeItemButton":true,"placeholder":true}'>
            <option value="" disabled selected>Select status...</option>
            <option value="draft">draft</option>
            <option value="scheduled">scheduled</option>
            <option value="published">published</option>
            <option value="archived">archived</option>
        </select>
        <div class="valid-feedback">Status selected.</div>
        <div class="invalid-feedback">Choose a status.</div>
    </div>

    <div class="col-md-3" style="z-index: 1098;">
        <label class="form-label" for="cl_is_pinned">Pinned</label>
        <select class="form-select" id="cl_is_pinned" data-choices="data-choices" size="1" required name="is_pinned" data-options='{"removeItemButton":true,"placeholder":true}'>
            <option value="" disabled selected>Pin to top?</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
        <div class="valid-feedback">Pin preference saved.</div>
        <div class="invalid-feedback">Select pin state.</div>
    </div>

    <div class="col-md-3" style="z-index: 1098;">
        <label class="form-label" for="cl_is_breaking">Breaking Change</label>
        <select class="form-select" id="cl_is_breaking" data-choices="data-choices" size="1" required name="is_breaking" data-options='{"removeItemButton":true,"placeholder":true}'>
            <option value="" disabled selected>Is breaking?</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
        <div class="valid-feedback">Flag set.</div>
        <div class="invalid-feedback">Select an option.</div>
    </div>

    <div class="col-md-3" style="z-index: 1098;">
        <label class="form-label" for="cl_scheduled_at">Scheduled At</label>
        <input class="form-control datetimepicker" id="cl_scheduled_at" name="scheduled_at" type="text" placeholder="D/M/Y H:i" data-options='{"enableTime":true,"time_24hr":true,"allowInput":true,"disableMobile":true}' />
        <div class="valid-feedback">Schedule set.</div>
        <div class="invalid-feedback">Enter a valid datetime (optional).</div>
    </div>

    <div class="col-12">
        <label class="form-label" for="cl_description_editor">Description</label>
        <textarea id="cl_description_editor" name="description"></textarea>
        <div class="form-text">Use the toolbar or paste raw HTML. TinyMCE keeps your classes and tags.</div>
        <div class="invalid-feedback d-block" id="cl_desc_error" style="display:none;">
            Please add a description.
        </div>
    </div>



    <input type="hidden" name="posted_by_admin_id" value="<?= htmlspecialchars((string)$adminId) ?>">

    <div class="col-12">
        <button class="btn btn-primary" type="submit">Create Changelog</button>
    </div>
</form>


<script>
    /* Quill shim peste TinyMCE/textarea. NU atinge handlerul tău. */
    (function() {
        "use strict";

        var EDITOR_ID = "cl_description_editor";

        function getEditor() {
            return (window.tinymce && tinymce.get(EDITOR_ID)) || null;
        }

        function getHTML() {
            var ed = getEditor();
            if (ed) return ed.getContent({
                format: "raw"
            }).trim();
            var ta = document.getElementById(EDITOR_ID);
            return ta ? (ta.value || "").trim() : "";
        }

        function setHTML(v) {
            var ed = getEditor();
            if (ed) ed.setContent(v || "", {
                format: "raw"
            });
            else {
                var ta = document.getElementById(EDITOR_ID);
                if (ta) ta.value = v || "";
            }
        }

        function getText() {
            var ed = getEditor();
            if (ed) return ed.getContent({
                format: "text"
            }).trim();
            var div = document.createElement("div");
            div.innerHTML = getHTML();
            return (div.textContent || "").trim();
        }

        // expune „quill” compatibil cu ce folosește handlerul tău
        window.quill = window.quill || {
            root: {},
            getText: getText
        };
        Object.defineProperty(window.quill.root, "innerHTML", {
            get: getHTML,
            set: setHTML
        });

        // variabilele pe care le folosești în submit
        window.editorEl = document.getElementById(EDITOR_ID);
        window.descError = document.getElementById("cl_desc_error");
        window.descInput = document.querySelector("textarea#" + EDITOR_ID);

        // fallback de bun-simț pentru notify dacă nu există
        window.notify = window.notify || function(msg) {
            try {
                console.warn(msg);
            } catch (e) {}
        };

        // dacă TinyMCE e încărcat dar nu inițializat, pornește-l minimal
        function bootTiny() {
            if (!window.tinymce || typeof tinymce.init !== "function") return;
            if (tinymce.get(EDITOR_ID)) return;
            tinymce.init({
                license_key: 'gpl',
                selector: "#" + EDITOR_ID,
                skin: "oxide-dark",
                content_css: "dark",
                branding: false,
                promotion: false,
                height: 360,
                menubar: true,
                plugins: [
                    "advlist", "autolink", "lists", "link", "image", "media",
                    "table", "charmap", "emoticons", "searchreplace", "visualblocks",
                    "visualchars", "fullscreen", "insertdatetime", "nonbreaking",
                    "anchor", "code", "codesample", "pagebreak", "quickbars", "wordcount",
                    "directionality"
                ],
                toolbar: [
                    "undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | removeformat",
                    "alignleft aligncenter alignright alignjustify | outdent indent | bullist numlist | subscript superscript | blockquote code codesample",
                    "link image media table | charmap emoticons anchor | ltr rtl | searchreplace visualblocks fullscreen"
                ].join(" \n"),
                valid_elements: "*[*]",
                extended_valid_elements: "*[*]",
                verify_html: false,
                convert_urls: false
            });
        }

        // încearcă puțin până apare tinymce
        var tries = 0,
            iv = setInterval(function() {
                tries++;
                if ((window.tinymce && typeof tinymce.init === "function") || tries > 40) {
                    clearInterval(iv);
                    bootTiny();
                }
            }, 100);
    })();
</script>