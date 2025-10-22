$(document).ready(function () {
  $("#star-rating span").on("click", function () {
    var value = $(this).data("value");
    $("#star-rating span").removeClass("selected");
    $("#star-rating span").each(function () {
      if ($(this).data("value") >= value) {
        $(this).addClass("selected");
      }
    });
  });

  $("#photo-upload").on("change", function () {
    var input = this;
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $("#photo-preview").attr("src", e.target.result).show();
      };
      reader.readAsDataURL(input.files[0]);
    }
  });

  // Form submission via AJAX
  $("#testimonial-form").on("submit", function (event) {
    event.preventDefault();

    // Validate required fields
    var author = $('input[name="author"]').val().trim();
    if (!author) {
      notify("Please enter the author name.", 5000);
      return;
    }
    var review = $('textarea[name="review"]').val().trim();
    if (!review) {
      notify("Please enter your testimonial.", 5000);
      return;
    }
    var stars = $("#star-rating span.selected").length;
    if (stars === 0) {
      notify("Please select a rating.", 5000);
      return;
    }
    var fileInput = $("#photo-upload")[0];
    if (fileInput.files.length === 0) {
      notify("Please upload a profile picture.", 5000);
      return;
    }

    var formData = new FormData(this);
    formData.set("stars", stars);

    $.ajax({
      url: "/backend/api/private/add/testimonial.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        var json =
          typeof response === "string" ? JSON.parse(response) : response;
        notify(json.message || "Testimonial submitted successfully!", 5000);
        $("#testimonial-form")[0].reset();
        $("#star-rating span").removeClass("selected");
        $("#photo-preview").hide();
      },
      error: function (xhr) {
        try {
          var json = JSON.parse(xhr.responseText);
          console.log(json.message || "An error occurred.");
        } catch (e) {
          console.log("An error occurred: " + xhr.responseText);
        }
        redirect("/testimonial");
      },
    });
  });
});

$(document).ready(function () {
  $("#create-regulation-form").on("submit", function (event) {
    event.preventDefault();

    var content = tinymce.get("regulation-editor").getContent();

    var category = $("select[name='category']").val();

    if (!category || !content) {
      notify("Please fill in all mandatory fields.", 5000);
      return false;
    }

    var formData = new FormData();
    formData.append("category", category);
    formData.append("content", content);

    $.ajax({
      url: "/backend/api/private/add/regulation.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function () {
        notify("Regulation has been successfully added!", 5000);
        $("#create-regulation-form")[0].reset();
        tinymce.get("regulation-editor").setContent("");
      },
      error: function (response) {
        console.log(response.responseText);
        try {
          var jsonResponse = JSON.parse(response.responseText);
          notify(jsonResponse.message || "Error sending the form.", 5000);
        } catch (e) {
          notify("An unexpected error occurred.", 5000);
        }
      },
    });
  });
});

$(document).ready(function () {
  $("#edit-regulation-form").on("submit", function (event) {
    event.preventDefault();

    var content = tinymce.get("regulation-editor").getContent();

    var category = $("select[name='category']").val();

    if (!category || !content) {
      notify("Please fill in all mandatory fields.", 5000);
      return false;
    }

    var formData = new FormData();
    formData.append("category", category);
    formData.append("content", content);

    $.ajax({
      url: "/backend/api/private/edit/regulation.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function () {
        notify("Regulation has been successfully edited!", 5000);
        $("#edit-regulation-form")[0].reset();
        tinymce.get("regulation-editor").setContent("");
      },
      error: function (response) {
        console.log(response.responseText);
        try {
          var jsonResponse = JSON.parse(response.responseText);
          notify(jsonResponse.message || "Error sending the form.", 5000);
        } catch (e) {
          notify("An unexpected error occurred.", 5000);
        }
      },
    });
  });
});

$(document).ready(function () {
  $("#contact-form").on("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    $.ajax({
      url: "/backend/api/public/contact.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function () {
        notify("Message sent successfully!", 5000);
        $("#contact-form")[0].reset();
      },
      error: function (response) {
        console.log(response.responseText);
        try {
          const json = JSON.parse(response.responseText);
          notify(json.message || "Error sending the message.", 5000);
        } catch (e) {
          notify("An unexpected error occurred.", 5000);
        }
      },
    });
  });
});

// Form validation and submission for registration
$("#registration-form").on("submit", function (event) {
  event.preventDefault();

  const $form = $(this);
  const getVal = (sel) => $.trim($(sel).val() || "");

  const fullName = getVal("#fullName");
  const fullNamePattern =
    /^[A-Za-zÀ-ÖØ-öø-ÿ'’.\-]+(?:\s+[A-Za-zÀ-ÖØ-öø-ÿ'’.\-]+)+$/;
  if (!fullNamePattern.test(fullName) || fullName.length < 3) {
    notify("Please enter your full name (first and last).", 5000);
    return false;
  }

  const email = getVal("#email");
  const emailPattern = /^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$/;
  if (!emailPattern.test(email)) {
    notify("Please enter a valid email address.", 5000);
    return false;
  }

  const password = $("#password").val();
  const passwordPattern =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.,;:!#()_\-+\[\]{}]).{8,}$/;
  if (!passwordPattern.test(password)) {
    notify(
      "Password must be at least 8 characters, with lowercase, uppercase, number, and special character.",
      5000
    );
    return false;
  }

  const confirmPassword = $("#confirm-password").val();
  if (password !== confirmPassword) {
    notify("Passwords do not match.", 5000);
    return false;
  }

  const phone = getVal("#phone");
  const phoneDigits = phone.replace(/\D/g, "");
  const phonePattern = /^[+]?[0-9\s().\-]{7,20}$/;
  if (!phonePattern.test(phone) || phoneDigits.length < 7) {
    notify("Please enter a valid phone number.", 5000);
    return false;
  }

  const role = $("#role").val();
  if (!role) {
    notify("Please select your role.", 5000);
    return false;
  }

  const orgType = $("#org_type").val();
  if (!orgType) {
    notify("Please select your organization type.", 5000);
    return false;
  }

  const orgName = getVal("#org_name");
  if (orgName.length < 2) {
    notify("Please enter your organization name.", 5000);
    return false;
  }

  const country = getVal("#country");
  if (country.length < 2) {
    notify("Please enter your country.", 5000);
    return false;
  }

  const city = getVal("#city");
  if (city.length < 2) {
    notify("Please enter your city.", 5000);
    return false;
  }

  let isValid = true;
  $(
    "#registration-form input[required], #registration-form select[required], #registration-form select[data-validation='required']"
  ).each(function () {
    if (!$.trim($(this).val())) {
      isValid = false;
      notify("Please fill all required fields.", 5000);
      return false;
    }
  });
  if (!isValid) return false;

  let formData = $form.serialize().replace(/%22/g, '"');
  const action =
    $form.attr("action") || "/backend/api/private/ucp/register.php";
  const $btn = $form.find("button[type='submit']");

  $btn.prop("disabled", true);

  $.ajax({
    url: action,
    type: "POST",
    data: formData,
    success: function () {
      notify("Registration successful! Redirecting...", 2500);
      setTimeout(function () {
        window.location.replace("?registration=success");
      }, 2500);
    },
    error: function (xhr) {
      try {
        const json = JSON.parse(xhr.responseText || "{}");
        notify(json.message || "Registration failed. Please try again.", 5000);
      } catch (e) {
        console.error(xhr.responseText);
        notify("Registration failed. Please try again.", 5000);
      }
    },
    complete: function () {
      $btn.prop("disabled", false);
    },
  });
});

$("#loginForm").on("submit", function (event) {
  event.preventDefault();

  const $form = $(this);
  const getVal = (sel) => $.trim($(sel).val() || "");

  const email = getVal("#email");
  const emailPattern = /^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$/;
  if (!emailPattern.test(email)) {
    notify("Please enter a valid email address.", 5000);
    return false;
  }

  const password = $("#password").val();
  if (!password) {
    notify("Please enter your password.", 5000);
    return false;
  }

  const arr = $form.serializeArray();
  const params = new URLSearchParams();
  arr.forEach(({ name, value }) => {
    if (name !== "remember-password") params.append(name, value);
  });
  params.append("remember", $("#remember-password").is(":checked") ? "1" : "0");

  const formData = params.toString();
  const action = $form.attr("action") || "/backend/api/private/ucp/login.php";
  const $btn = $form.find("button[type='submit']");

  $btn.prop("disabled", true);

  $.ajax({
    url: action,
    type: "POST",
    data: formData,
    success: function (res) {
      notify("Login successful! Redirecting...", 2000);

      try {
        const json = typeof res === "string" ? JSON.parse(res) : res;
        if (json && json.redirect) {
          setTimeout(function () {
            window.location.replace(json.redirect);
          }, 1500);
          return;
        }
      } catch (e) {}

      setTimeout(function () {
        const qs = new URLSearchParams(window.location.search);
        const next = qs.get("next");
        window.location.replace(next || "/ucp/");
      }, 1500);
    },
    error: function (xhr) {
      let sentAlert = false;
      try {
        const json = JSON.parse(xhr.responseText || "{}");
        const code = String(json.error_code || json.code || "").toUpperCase();
        const reason = String(
          json.reason || (json.meta && json.meta.reason) || ""
        ).toLowerCase();

        if (
          code === "WRONG_PASSWORD" ||
          code === "INVALID_CREDENTIALS" ||
          reason === "password"
        ) {
          sentAlert = true;
        }

        notify(json.message || "Login failed. Please try again.", 5000);
      } catch (e) {
        if (xhr.status === 401) {
          sentAlert = true;
        }
        console.error(xhr.responseText);
        notify("Login failed. Please try again.", 5000);
      } finally {
        if (sentAlert) {
          notify(
            "We noticed a failed sign-in. If this wasn't you, check your email.",
            4000
          );
        }
      }
    },
    complete: function () {
      $btn.prop("disabled", false);
    },
  });
});

function isEmail(v) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
}
function isPhone(v) {
  return /^\+?[1-9]\d{1,14}$/.test(v);
}

function initTeamLogoDropzone(selector) {
  let dz = null;
  try {
    dz = Dropzone.forElement(selector);
  } catch (e) {}
  if (!dz) {
    Dropzone.autoDiscover = false;
    dz = new Dropzone(selector, {
      url: "/backend/api/private/uploadLogo.php",
      paramName: "logo",
      maxFiles: 1,
      maxFilesize: 5,
      acceptedFiles: "image/jpeg,image/png,image/webp",
      autoProcessQueue: false,
      addRemoveLinks: false,
    });
  }
  return dz;
}

// Separate upload function (returns a Promise that resolves with { path })
function uploadLogo(teamLogoDZ) {
  return new Promise(function (resolve, reject) {
    if (!teamLogoDZ || teamLogoDZ.files.length === 0) {
      return reject({
        message: "Team Logo is required. Please upload an image.",
      });
    }
    const fd = new FormData();
    fd.append("logo", teamLogoDZ.files[0]);

    $.ajax({
      url: "/backend/api/private/uploadLogo.php",
      type: "POST",
      data: fd,
      processData: false,
      contentType: false,
    })
      .done(function (resp) {
        let json = resp;
        if (typeof resp === "string") {
          try {
            json = JSON.parse(resp);
          } catch (_) {
            json = {};
          }
        }
        if (json && json.ok && json.path) resolve({ path: json.path });
        else
          reject({
            message: (json && json.message) || "Failed to upload logo.",
          });
      })
      .fail(function (xhr) {
        let msg = "Logo upload failed.";
        try {
          msg = JSON.parse(xhr.responseText).message || msg;
        } catch (_) {}
        reject({ message: msg });
      });
  });
}

// Bind submit on ready (yes, globally)
function onTeamCreateReady() {
  const $form = $("#team_create_form");
  if ($form.length === 0) return;

  const teamLogoDZ = initTeamLogoDropzone("#team_logo_dropzone");

  $form.on("submit", function (e) {
    e.preventDefault();

    const v = (name) =>
      ($form.find('[name="' + name + '"]').val() || "").trim();

    let team_code = v("team_code");
    const team_name = v("team_name");
    const team_city = v("team_city");
    const team_country = v("team_country");
    const org_name = v("org_name");
    let team_website = v("team_website");
    const team_email = v("team_email");
    const team_phone = v("team_phone");
    const user_id = v("user_id"); // if present; backend can ignore
    const agreed = $("#invalidCheck").is(":checked");

    if (team_website && !/^https?:\/\//i.test(team_website)) {
      team_website = "https://" + team_website;
      $form.find('[name="team_website"]').val(team_website);
    }

    if (
      !team_code ||
      !team_name ||
      !team_city ||
      !team_country ||
      !org_name ||
      !team_website ||
      !team_email ||
      !team_phone ||
      !agreed
    ) {
      notify("Please fill in all mandatory fields and accept the terms.", 5000);
      return;
    }
    if (!isEmail(team_email)) {
      notify(
        "Please enter a valid email address (e.g. name@domain.com).",
        5000
      );
      return;
    }
    if (!isPhone(team_phone)) {
      notify("Please provide a valid international phone number.", 5000);
      return;
    }
    if (!teamLogoDZ || teamLogoDZ.files.length === 0) {
      notify("Team Logo is required. Please upload an image.", 5000);
      return;
    }

    const $btn = $form.find('button[type="submit"]').prop("disabled", true);

    uploadLogo(teamLogoDZ)
      .then(function (res) {
        const createFD = new FormData();
        createFD.append("team_code", team_code);
        createFD.append("team_name", team_name);
        createFD.append("team_city", team_city);
        createFD.append("team_country", team_country);
        createFD.append("org_name", org_name);
        createFD.append("team_website", team_website);
        createFD.append("team_email", team_email);
        createFD.append("team_phone", team_phone);
        createFD.append("team_logo", res.path);
        if (user_id) createFD.append("user_id", user_id);

        return $.ajax({
          url: "/backend/api/private/ucp/team/create.php",
          type: "POST",
          data: createFD,
          processData: false,
          contentType: false,
        });
      })
      .then(function () {
        notify("Team created successfully!", 5000);
        setTimeout(function () {
          window.location.replace("/ucp/team/view");
        }, 2500);
      })
      .catch(function (xhr) {
        let err = "Error creating team.";
        try {
          err = JSON.parse(xhr.responseText).message || err;
        } catch (_) {}
        notify(err, 6000);
      })
      .finally(function () {
        $btn.prop("disabled", false);
      });
  });
}

$(document).ready(onTeamCreateReady);

$("#team_edit_form").on("submit", function (event) {
  event.preventDefault();

  const $form = $(this);
  const v = (name) => ($form.find('[name="' + name + '"]').val() || "").trim();

  const team_name = v("team_name");
  const team_city = v("team_city");
  const team_country = v("team_country");
  const org_name = v("org_name");
  let team_website = v("team_website");
  const team_email = v("team_email");
  const team_phone = v("team_phone");
  const user_id = v("user_id");

  if (team_website && !/^https?:\/\//i.test(team_website)) {
    team_website = "https://" + team_website;
    $form.find('[name="team_website"]').val(team_website);
  }

  if (
    !team_name ||
    !team_city ||
    !team_country ||
    !org_name ||
    !team_website ||
    !team_email ||
    !team_phone
  ) {
    notify("Please fill in all mandatory fields.", 5000);
    return;
  }
  if (!isEmail(team_email)) {
    notify("Please enter a valid email address (e.g. name@domain.com).", 5000);
    return;
  }
  if (!isPhone(team_phone)) {
    notify("Please provide a valid international phone number.", 5000);
    return;
  }

  const $btn = $form.find('button[type="submit"]').prop("disabled", true);

  const editFD = new FormData();
  editFD.append("team_name", team_name);
  editFD.append("team_city", team_city);
  editFD.append("team_country", team_country);
  editFD.append("org_name", org_name);
  editFD.append("team_website", team_website);
  editFD.append("team_email", team_email);
  editFD.append("team_phone", team_phone);
  if (user_id) editFD.append("user_id", user_id);

  $.ajax({
    url: "/backend/api/private/ucp/team/edit.php",
    type: "POST",
    data: editFD,
    processData: false,
    contentType: false,
    success: function () {
      notify("Team updated successfully!", 5000);
      setTimeout(function () {
        window.location.replace("/ucp/team/view");
      }, 2500);
    },
    error: function (xhr) {
      let err = "Error updating team.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
    complete: function () {
      $btn.prop("disabled", false);
    },
  });
});

$("#member_create_form").on("submit", function (event) {
  event.preventDefault();

  const $form = $(this);
  const getVal = (sel) => $.trim($form.find(sel).val() || "");

  const firstName = getVal("#member_first_name");
  const lastName = getVal("#member_last_name");
  const email = getVal("#member_email");
  const phone = getVal("#member_phone");
  const dob = getVal("#member_dob");
  const tshirt = getVal("#member_tshirt");
  const emergencyPhone = getVal("#member_emergency_phone");
  const role = getVal("#member_role");
  const agreed = $("#invalidCheck").is(":checked");
  const userId = $form.find('[name="user_id"]').val();
  const teamId = $form.find('[name="team_id"]').val();

  const fullName = lastName + " " + firstName;

  if (!firstName || firstName.length < 2) {
    notify(
      "Please enter the member's first name (at least 2 characters).",
      5000
    );
    return;
  }
  if (!lastName || lastName.length < 2) {
    notify(
      "Please enter the member's last name (at least 2 characters).",
      5000
    );
    return;
  }
  if (!isEmail(email)) {
    notify("Please enter a valid email address.", 5000);
    return;
  }
  if (!isPhone(phone)) {
    notify("Please enter a valid phone number.", 5000);
    return;
  }
  if (!dob) {
    notify("Please enter the member's date of birth.", 5000);
    return;
  }
  const dobDate = new Date(dob.split("/").reverse().join("-"));
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  if (dobDate > today) {
    notify("Date of birth cannot be in the future.", 5000);
    return;
  }
  if (!tshirt) {
    notify("Please select the member's T-shirt size.", 5000);
    return;
  }
  if (!isPhone(emergencyPhone)) {
    notify("Please enter a valid emergency phone number.", 5000);
    return;
  }
  if (!role) {
    notify("Please select the member's role.", 5000);
    return;
  }
  if (!agreed) {
    notify("You must confirm photo consent before proceeding.", 5000);
    return;
  }
  if (!teamId) {
    notify("Team ID is required.", 5000);
    return;
  }

  const $btn = $form.find('button[type="submit"]').prop("disabled", true);

  const fd = new FormData();
  fd.append("member_fullname", fullName);
  fd.append("member_email", email);
  fd.append("member_phone", phone);
  fd.append("member_dob", dob);
  fd.append("member_tshirt", tshirt);
  fd.append("member_emergency_phone", emergencyPhone);
  fd.append("member_role", role);
  fd.append("user_id", userId);
  fd.append("team_id", teamId);

  $.ajax({
    url: "/backend/api/private/ucp/members/create.php",
    type: "POST",
    data: fd,
    processData: false,
    contentType: false,
    success: function () {
      notify("Member added successfully!", 5000);
      setTimeout(function () {
        window.location.replace("/ucp/members/view");
      }, 2500);
    },
    error: function (xhr) {
      console.error(xhr);
      let err = "Error adding the member.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
    complete: function () {
      $btn.prop("disabled", false);
    },
  });
});

$("#member_edit_form").on("submit", function (event) {
  event.preventDefault();

  const $form = $(this);
  const getVal = (sel) => $.trim($form.find(sel).val() || "");

  const firstName = getVal("#member_first_name");
  const lastName = getVal("#member_last_name");
  const email = getVal("#member_email");
  const phone = getVal("#member_phone");
  const dob = getVal("#member_dob");
  const tshirt = getVal("#member_tshirt");
  const emergencyPhone = getVal("#member_emergency_phone");
  const role = getVal("#member_role");
  const agreed = 1;
  const userId = $form.find('[name="user_id"]').val();
  const teamId = $form.find('[name="team_id"]').val();
  const memberId = $form.find('[name="member_id"]').val();

  const fullName = lastName + " " + firstName;

  if (!memberId) {
    notify("Member ID is required.", 5000);
    return;
  }
  if (!firstName || firstName.length < 2) {
    notify(
      "Please enter the member's first name (at least 2 characters).",
      5000
    );
    return;
  }
  if (!lastName || lastName.length < 2) {
    notify(
      "Please enter the member's last name (at least 2 characters).",
      5000
    );
    return;
  }
  if (!isEmail(email)) {
    notify("Please enter a valid email address.", 5000);
    return;
  }
  if (!isPhone(phone)) {
    notify("Please enter a valid phone number.", 5000);
    return;
  }
  if (!dob) {
    notify("Please enter the member's date of birth.", 5000);
    return;
  }
  const dobDate = new Date(dob.split("/").reverse().join("-"));
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  if (dobDate > today) {
    notify("Date of birth cannot be in the future.", 5000);
    return;
  }
  if (!tshirt) {
    notify("Please select the member's T-shirt size.", 5000);
    return;
  }
  if (!isPhone(emergencyPhone)) {
    notify("Please enter a valid emergency phone number.", 5000);
    return;
  }
  if (!role) {
    notify("Please select the member's role.", 5000);
    return;
  }
  if (!teamId) {
    notify("Team ID is required.", 5000);
    return;
  }

  const $btn = $form.find('button[type="submit"]').prop("disabled", true);

  const fd = new FormData();
  fd.append("member_id", memberId);
  fd.append("member_fullname", fullName);
  fd.append("member_email", email);
  fd.append("member_phone", phone);
  fd.append("member_dob", dob);
  fd.append("member_tshirt", tshirt);
  fd.append("member_emergency_phone", emergencyPhone);
  fd.append("member_role", role);
  fd.append("user_id", userId);
  fd.append("team_id", teamId);

  $.ajax({
    url: "/backend/api/private/ucp/members/edit.php",
    type: "POST",
    data: fd,
    processData: false,
    contentType: false,
    success: function () {
      notify("Member updated successfully!", 5000);
      setTimeout(function () {
        window.location.replace("/ucp/members/view");
      }, 2500);
    },
    error: function (xhr) {
      console.error(xhr);
      let err = "Error updating the member.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
    complete: function () {
      $btn.prop("disabled", false);
    },
  });
});

// =-- ROBOTS SELECT ---

$(function () {
  function hardResetChoices(selectId, optsAttr = null) {
    const hidden = document.getElementById(selectId);
    if (!hidden) return;

    const wrapper = hidden.closest(".choices");
    if (!wrapper) return; // deja curat

    const clone = document.createElement("select");
    clone.id = hidden.id;
    clone.name = hidden.name || "";
    clone.className = hidden.className || "form-select";
    clone.multiple = hidden.multiple;
    clone.size = hidden.size || 1;

    clone.setAttribute(
      "data-choices",
      hidden.getAttribute("data-choices") || "data-choices"
    );
    clone.setAttribute(
      "data-options",
      optsAttr ??
        (hidden.getAttribute("data-options") ||
          '{"removeItemButton":true,"placeholder":true}')
    );

    Array.from(hidden.options).forEach((opt) => {
      const o = document.createElement("option");
      o.value = opt.value;
      o.textContent = opt.textContent;
      if (opt.disabled) o.disabled = true;
      if (opt.selected) o.selected = true;
      clone.appendChild(o);
    });

    wrapper.parentNode.replaceChild(clone, wrapper);
  }

  hardResetChoices("robot_category");
  hardResetChoices(
    "robot_operator",
    '{"removeItemButton":false,"placeholder":true}'
  );
  hardResetChoices("robot_members");

  const HUMANOID = ["category_humanoid-triathlon", "category_humanoid-sumo"];
  const LINE = [
    "category_line-follower-classic",
    "category_line-follower-turbo",
    "category_line-follower-enhanced",
    "category_drag-race",
  ];

  const catEl = document.getElementById("robot_category");
  const catChoices = new Choices(catEl, {
    removeItemButton: true,
    shouldSort: false,
    addChoices: false,
  });
  const catBacking = catChoices.passedElement.element;

  const catGet = () => catChoices.getValue(true);
  const catIsHum = (arr) => arr.some((v) => HUMANOID.includes(v));
  const catIsLine = (arr) => arr.some((v) => LINE.includes(v));

  function catEnforceSelection() {
    let sel = catGet();
    if (!sel.length) return;

    if (catIsHum(sel)) {
      const keep = sel.filter((v) => HUMANOID.includes(v));
      if (keep.length !== sel.length) {
        catChoices.removeActiveItems();
        catChoices.setChoiceByValue(keep);
      }
    } else if (catIsLine(sel)) {
      const keep = sel.filter((v) => LINE.includes(v));
      if (keep.length !== sel.length) {
        catChoices.removeActiveItems();
        catChoices.setChoiceByValue(keep);
      }
    } else {
      const last = sel[sel.length - 1];
      if (sel.length !== 1) {
        catChoices.removeActiveItems();
        catChoices.setChoiceByValue(last);
      }
    }
  }

  function catApplyDisabledMask() {
    const sel = catGet();
    const inHum = catIsHum(sel);
    const inLine = catIsLine(sel);
    const solo = sel.length && !inHum && !inLine;

    Array.from(catBacking.options).forEach((o) => {
      if (o.value === "") o.disabled = true;
      else o.disabled = false;
    });

    if (inHum) {
      Array.from(catBacking.options).forEach((o) => {
        if (o.value && !HUMANOID.includes(o.value)) o.disabled = true;
      });
    } else if (inLine) {
      Array.from(catBacking.options).forEach((o) => {
        if (o.value && !LINE.includes(o.value)) o.disabled = true;
      });
    } else if (solo) {
      const keep = new Set(sel);
      Array.from(catBacking.options).forEach((o) => {
        if (o.value && !keep.has(o.value)) o.disabled = true;
      });
    }

    catChoices.refresh(false, false);
  }

  catEl.addEventListener("addItem", () => {
    catEnforceSelection();
    catApplyDisabledMask();
  });
  catEl.addEventListener("removeItem", () => {
    catEnforceSelection();
    catApplyDisabledMask();
  });
  catApplyDisabledMask();

  const opEl = document.getElementById("robot_operator");
  const opChoices = new Choices(opEl, {
    removeItemButton: false,
    shouldSort: false,
    addChoices: false,
    searchEnabled: true,
  });
  const opBacking = opChoices.passedElement.element;

  const memEl = document.getElementById("robot_members");
  const memChoices = new Choices(memEl, {
    removeItemButton: true,
    shouldSort: false,
    addChoices: false,
  });
  const memBacking = memChoices.passedElement.element;

  function getOperatorValue() {
    const val = opChoices.getValue(true);
    return Array.isArray(val) ? val[0] || "" : val || "";
  }
  function getMembersValues() {
    const v = memChoices.getValue(true);
    return Array.isArray(v) ? v : v ? [v] : [];
  }

  function applyMembersMask() {
    const opVal = getOperatorValue();

    Array.from(memBacking.options).forEach((o) => {
      if (o.value === "") {
        o.disabled = true;
      } else {
        o.disabled = opVal && o.value === opVal;
      }
    });

    const members = getMembersValues();
    if (opVal && members.includes(opVal)) {
      memChoices.removeActiveItemsByValue(opVal);
    }

    memChoices.refresh(false, false);
  }

  function enforceMembersMax() {
    const vals = getMembersValues();
    if (vals.length <= 4) return;

    const keep = new Set(vals.slice(0, 4));
    vals.forEach((v) => {
      if (!keep.has(v)) memChoices.removeActiveItemsByValue(v);
    });
  }

  opEl.addEventListener("change", function () {
    applyMembersMask();
  });

  memEl.addEventListener("addItem", function () {
    enforceMembersMax();
  });
  memEl.addEventListener("removeItem", function () {
    enforceMembersMax();
    applyMembersMask();
  });

  applyMembersMask();

  const membersCount = $("#robot_members option").length - 1; // exclude placeholder
  if (membersCount === 1) {
    const onlyMember = $("#robot_members option:not([value=''])").val();
    $("#robot_operator").val(onlyMember).prop("disabled", true);
    $("#robot_members").val([onlyMember]).prop("disabled", true);
    $("#robot_operator").closest(".col-md-3").hide();
    $("#robot_members").closest(".col-md-3").hide();
  }
});

// =-- ROBOTS SELECT ---

$("#robot_create_form").on("submit", function (event) {
  event.preventDefault();

  const $form = $(this);
  const getVal = (sel) => $.trim($form.find(sel).val() || "");

  const robotName = getVal("#robot_name");

  // --- fix pentru categorii (ia direct din option:selected) ---
  const $catSel = $form.find("#robot_category");
  let categories = $catSel
    .find("option:selected")
    .map(function () {
      return (this.value || "").trim();
    })
    .get()
    .filter(Boolean)
    .map((cat) => cat.replace(/^category_/, ""));
  console.log("DEBUG categories:", categories);

  const operator = getVal("#robot_operator");

  // --- normalize members ---
  let rawMembers = $form.find("#robot_members").val();
  let members = [];
  if (Array.isArray(rawMembers)) {
    members = rawMembers;
  } else if (typeof rawMembers === "string" && rawMembers !== "") {
    members = [rawMembers];
  }
  members = members.map((mem) =>
    typeof mem === "string" ? mem.replace(/^member_/, "") : mem
  );

  // --- fix pentru echipă cu un singur membru ---
  if (members.length === 0 && $("#robot_members_group").is(":hidden")) {
    const hiddenMember = $form.find('input[name="robot_members[]"]').val();
    if (hiddenMember) members = [hiddenMember];
  }

  const agreed = $("#invalidCheck").is(":checked");
  const userId = $form.find('[name="user_id"]').val();
  const teamId = $form.find('[name="team_id"]').val();

  // --- validări ---
  if (!robotName || robotName.length < 2) {
    notify("Please enter the robot's name (at least 2 characters).", 5000);
    return;
  }
  if (!categories.length) {
    notify("Please select one or more robot categories.", 5000);
    return;
  }
  if (!operator && $("#robot_operator_group").is(":visible")) {
    notify("Please select the robot operator.", 5000);
    return;
  }
  if (!members.length) {
    notify("Please select at least one robot member.", 5000);
    return;
  }
  if (!agreed) {
    notify("You must confirm safety consent before proceeding.", 5000);
    return;
  }
  if (!teamId) {
    notify("Team ID is required.", 5000);
    return;
  }

  const $btn = $form.find('button[type="submit"]').prop("disabled", true);

  // --- trimite prin AJAX ---
  const fd = new FormData();
  fd.append("robot_name", robotName);
  categories.forEach((cat) => fd.append("robot_category[]", cat));
  fd.append("robot_operator", operator.replace(/^member_/, ""));
  members.forEach((mem) => fd.append("robot_members[]", mem));
  fd.append("user_id", userId);
  fd.append("team_id", teamId);

  $.ajax({
    url: "/backend/api/private/ucp/robots/create.php",
    type: "POST",
    data: fd,
    processData: false,
    contentType: false,
    success: function () {
      notify("Robot added successfully!", 5000);
      setTimeout(function () {
        window.location.replace("/ucp/robots/view");
      }, 2500);
    },
    error: function (xhr) {
      console.error(xhr);
      let err = "Error adding the robot.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
    complete: function () {
      $btn.prop("disabled", false);
    },
  });
});

$("#robot_edit_form").on("submit", function (event) {
  event.preventDefault();

  const $form = $(this);
  const getVal = (sel) => $.trim($form.find(sel).val() || "");

  const robotName = getVal("#robot_name");

  // --- categorii: citește direct din <option:selected> ---
  const $catSel = $form.find("#robot_category");
  let categories = $catSel
    .find("option:selected")
    .map(function () {
      return (this.value || "").trim();
    })
    .get()
    .filter(Boolean)
    .map((cat) => cat.replace(/^category_/, ""));
  // console.log("DEBUG categories:", categories);

  const operator = getVal("#robot_operator");

  // --- membri: citește direct din <option:selected> ca să eviți .val() inconsistent ---
  const $memSel = $form.find("#robot_members");
  let members = $memSel
    .find("option:selected")
    .map(function () {
      return (this.value || "").trim();
    })
    .get()
    .filter(Boolean)
    .map((mem) => mem.replace(/^member_/, ""));
  // console.log("DEBUG members:", members);

  // --- fallback: dacă selectul e ascuns (echipă cu 1 membru) ia din hidden input ---
  if (members.length === 0 && $("#robot_members_group").is(":hidden")) {
    const hiddenMember = $form.find('input[name="robot_members[]"]').val();
    if (hiddenMember) members = [String(hiddenMember).replace(/^member_/, "")];
  }

  const agreed = $("#invalidCheck").is(":checked");
  const userId = $form.find('[name="user_id"]').val();
  const teamId = $form.find('[name="team_id"]').val();
  const robotId = $form.find('[name="robot_id"]').val();

  // --- validări ---
  if (!robotName || robotName.length < 2) {
    notify("Please enter the robot's name (at least 2 characters).", 5000);
    return;
  }
  if (!categories.length) {
    notify("Please select one or more robot categories.", 5000);
    return;
  }
  if (!operator && $("#robot_operator_group").is(":visible")) {
    notify("Please select the robot operator.", 5000);
    return;
  }
  if (!members.length) {
    notify("Please select at least one robot member.", 5000);
    return;
  }
  if (!agreed) {
    notify("You must confirm safety consent before proceeding.", 5000);
    return;
  }
  if (!teamId) {
    notify("Team ID is required.", 5000);
    return;
  }
  if (!robotId) {
    notify("Robot ID is required.", 5000);
    return;
  }

  const $btn = $form.find('button[type="submit"]').prop("disabled", true);

  // --- payload ---
  const fd = new FormData();
  fd.append("robot_id", robotId);
  fd.append("robot_name", robotName);
  categories.forEach((cat) => fd.append("robot_category[]", cat));
  fd.append("robot_operator", operator.replace(/^member_/, ""));
  members.forEach((mem) => fd.append("robot_members[]", mem));
  fd.append("user_id", userId);
  fd.append("team_id", teamId);

  $.ajax({
    url: "/backend/api/private/ucp/robots/edit.php",
    type: "POST",
    data: fd,
    processData: false,
    contentType: false,
    success: function () {
      notify("Robot updated successfully!", 5000);
      setTimeout(function () {
        window.location.replace("/ucp/robots/view");
      }, 2500);
    },
    error: function (xhr) {
      console.error(xhr);
      let err = "Error updating the robot.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
    complete: function () {
      $btn.prop("disabled", false);
    },
  });
});

$("#forgot-email").on("submit", function (event) {
  event.preventDefault();

  const email = $.trim($("#forgot-email input[type='email']").val() || "");
  if (!email) {
    notify("Please enter your email address.", 5000);
    return;
  }
  // Validare simplă email
  if (!/^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$/.test(email)) {
    notify("Please enter a valid email address.", 5000);
    return;
  }

  const formData = new FormData();
  formData.append("email", email);

  const $btn = $("#forgot-email button[type='submit']").prop("disabled", true);

  $.ajax({
    url: "/backend/api/private/ucp/forgot-password.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      let msg = "If this email exists, you will receive a reset link.";
      try {
        const json =
          typeof response === "string" ? JSON.parse(response) : response;
        msg = json.message || msg;
      } catch (_) {}
      notify(msg, 6000);
      $("#forgot-email")[0].reset();
    },
    error: function (xhr) {
      let err = "Error sending reset request.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
    complete: function () {
      $btn.prop("disabled", false);
    },
  });
});

$("#reset-password").on("submit", function (event) {
  event.preventDefault();

  var newPassword = $.trim($("#new-password").val() || "");
  var confirmPassword = $.trim($("#confirm-password").val() || "");
  var token = $.trim($("#token").val() || "");

  if (!newPassword || newPassword.length < 6) {
    notify("Password must be at least 6 characters.", 5000);
    return;
  }
  if (newPassword !== confirmPassword) {
    notify("Passwords do not match.", 5000);
    return;
  }
  if (!token) {
    notify("Invalid or missing reset token.", 5000);
    return;
  }

  var formData = new FormData();
  formData.append("new_password", newPassword);
  formData.append("token", token);

  var $btn = $("#reset-password button[type='submit']");
  var originalBtnHtml = $btn.html();
  $btn
    .prop("disabled", true)
    .html(
      '<span class="btn-wrap"><span class="text-one">Working...</span><span class="text-two">Working...</span></span>'
    );

  $.ajax({
    url: "/backend/api/private/ucp/reset-password.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      var msg = "Password reset successful!";
      try {
        var json =
          typeof response === "string" ? JSON.parse(response) : response;
        if (json && json.message) {
          msg = json.message;
        }
      } catch (e) {}
      notify(msg, 6000);
      $("#reset-password")[0].reset();
      setTimeout(function () {
        window.location.replace("/ucp/login");
      }, 2500);
    },
    error: function (xhr) {
      var err = "Error resetting password.";
      try {
        var parsed = JSON.parse(xhr.responseText);
        if (parsed && parsed.message) {
          err = parsed.message;
        }
      } catch (e) {}
      notify(err, 6000);
    },
    complete: function () {
      $btn.prop("disabled", false).html(originalBtnHtml);
    },
  });
});

function deleteMember(teamId, memberId) {
  if (!teamId || !memberId) {
    notify("Team ID and Member ID are required.", 5000);
    return;
  }

  if (
    !confirm(
      "Are you sure you want to delete this member?This action is irreversible!"
    )
  ) {
    return;
  }

  const formData = new FormData();
  formData.append("team_id", teamId);
  formData.append("member_id", memberId);

  $.ajax({
    url: "/backend/api/private/ucp/members/delete.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function () {
      notify("Member deleted successfully!", 5000);
      setTimeout(function () {
        window.location.replace("/ucp/members/view");
      }, 2500);
    },
    error: function (xhr) {
      let err = "Error deleting the member.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
  });
}

function deleteRobot(teamId, robotId) {
  if (!teamId || !robotId) {
    notify("Team ID and Robot ID are required.", 5000);
    return;
  }

  if (
    !confirm(
      "Are you sure you want to delete this robot? This action is irreversible!"
    )
  ) {
    return;
  }

  const formData = new FormData();
  formData.append("team_id", teamId);
  formData.append("robot_id", robotId);

  $.ajax({
    url: "/backend/api/private/ucp/robots/delete.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function () {
      notify("Robot deleted successfully!", 5000);
      setTimeout(function () {
        window.location.replace("/ucp/robots/view");
      }, 2500);
    },
    error: function (xhr) {
      let err = "Error deleting the robot.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
  });
}

// Admin Management

$("#admin_create_coach").on("submit", function (event) {
  event.preventDefault();

  const $form = $(this);
  const getVal = (sel) => $.trim($(sel).val() || "");

  const fullName = getVal("#user_full");
  const fullNamePattern =
    /^[A-Za-zÀ-ÖØ-öø-ÿ'’.\-]+(?:\s+[A-Za-zÀ-ÖØ-öø-ÿ'’.\-]+)+$/;
  if (!fullNamePattern.test(fullName) || fullName.length < 3) {
    notify("Please enter your full name (first and last).", 5000);
    return false;
  }

  const email = getVal("#user_email");
  const emailPattern = /^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$/;
  if (!emailPattern.test(email)) {
    notify("Please enter a valid email address.", 5000);
    return false;
  }

  const password = $("#user_password").val();
  const passwordPattern =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.,;:!#()_\-+\[\]{}]).{8,}$/;
  if (!passwordPattern.test(password)) {
    notify(
      "Password must be at least 8 characters, with lowercase, uppercase, number, and special character.",
      5000
    );
    return false;
  }

  const confirmPassword = $("#user_password").val();
  if (password !== confirmPassword) {
    notify("Passwords do not match.", 5000);
    return false;
  }

  const phone = getVal("#user_phone");
  const phoneDigits = phone.replace(/\D/g, "");
  const phonePattern = /^[+]?[0-9\s().\-]{7,20}$/;
  if (!phonePattern.test(phone) || phoneDigits.length < 7) {
    notify("Please enter a valid phone number.", 5000);
    return false;
  }

  const role = $("#user_role").val();
  if (!role) {
    notify("Please select your role.", 5000);
    return false;
  }

  const orgType = $("#user_organization").val();
  if (!orgType) {
    notify("Please select your organization type.", 5000);
    return false;
  }

  const orgName = getVal("#user_org_name");
  if (orgName.length < 2) {
    notify("Please enter your organization name.", 5000);
    return false;
  }

  const country = getVal("#user_country");
  if (country.length < 2) {
    notify("Please enter your country.", 5000);
    return false;
  }

  const city = getVal("#user_city");
  if (city.length < 2) {
    notify("Please enter your city.", 5000);
    return false;
  }

  let isValid = true;
  $(
    "#admin_create_coach input[required], #admin_create_coach select[required], #admin_create_coach select[data-validation='required']"
  ).each(function () {
    if (!$.trim($(this).val())) {
      isValid = false;
      notify("Please fill all required fields.", 5000);
      return false;
    }
  });
  if (!isValid) return false;

  const formData = new FormData();
  formData.append("fullName", fullName);
  formData.append("email", email);
  formData.append("password", password);
  formData.append("confirm-password", password);
  formData.append("phone", phone);
  formData.append("role", role);
  formData.append("org_type", orgType);
  formData.append("org_name", orgName);
  formData.append("country", country);
  formData.append("city", city);

  const action =
    $form.attr("action") || "/backend/api/private/ucp/register.php";
  const $btn = $form.find("button[type='submit']");

  $btn.prop("disabled", true);

  $.ajax({
    url: action,
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function () {
      notify("Registration successful! Redirecting...", 2500);
      setTimeout(function () {
        window.location.replace("/ucp/admin/users/manage");
      }, 2500);
    },
    error: function (xhr) {
      try {
        const json = JSON.parse(xhr.responseText || "{}");
        notify(json.message || "Registration failed. Please try again.", 5000);
      } catch (e) {
        console.error(xhr.responseText);
        notify("Registration failed. Please try again.", 5000);
      }
    },
    complete: function () {
      $btn.prop("disabled", false);
    },
  });
});

$("#admin_edit_coach").on("submit", function (event) {
  event.preventDefault();

  const $form = $(this);
  const getVal = (sel) => $.trim($(sel).val() || "");

  const fullName = getVal("#user_full");
  const fullNamePattern =
    /^[A-Za-zÀ-ÖØ-öø-ÿ'’.\-]+(?:\s+[A-Za-zÀ-ÖØ-öø-ÿ'’.\-]+)+$/;
  if (!fullNamePattern.test(fullName) || fullName.length < 3) {
    notify("Please enter your full name (first and last).", 5000);
    return false;
  }

  const email = getVal("#user_email");
  const emailPattern = /^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$/;
  if (!emailPattern.test(email)) {
    notify("Please enter a valid email address.", 5000);
    return false;
  }

  const password = $("#user_password").val();
  const confirmPassword = $("#user_password").val();
  if (password) {
    const passwordPattern =
      /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.,;:!#()_\-+\[\]{}]).{8,}$/;
    if (!passwordPattern.test(password)) {
      notify(
        "Password must be at least 8 characters, with lowercase, uppercase, number, and special character.",
        5000
      );
      return false;
    }

    if (password !== confirmPassword) {
      notify("Passwords do not match.", 5000);
      return false;
    }
  }

  const phone = getVal("#user_phone");
  const phoneDigits = phone.replace(/\D/g, "");
  const phonePattern = /^[+]?[0-9\s().\-]{7,20}$/;
  if (!phonePattern.test(phone) || phoneDigits.length < 7) {
    notify("Please enter a valid phone number.", 5000);
    return false;
  }

  const orgType = $("#user_organization").val();
  if (!orgType) {
    notify("Please select your organization type.", 5000);
    return false;
  }

  const orgName = getVal("#user_org_name");
  if (orgName.length < 2) {
    notify("Please enter your organization name.", 5000);
    return false;
  }

  const country = getVal("#user_country");
  if (country.length < 2) {
    notify("Please enter your country.", 5000);
    return false;
  }

  const city = getVal("#user_city");
  if (city.length < 2) {
    notify("Please enter your city.", 5000);
    return false;
  }

  let isValid = true;
  $(
    "#admin_edit_coach input[required], #admin_edit_coach select[required], #admin_edit_coach select[data-validation='required']"
  ).each(function () {
    if (!$.trim($(this).val())) {
      isValid = false;
      notify("Please fill all required fields.", 5000);
      return false;
    }
  });
  if (!isValid) return false;

  const formData = new FormData();
  formData.append("user_id", $("#user_id").val());
  formData.append("fullName", fullName);
  formData.append("email", email);
  if (password) {
    formData.append("password", password);
    formData.append("confirm-password", password);
  }
  formData.append("phone", phone);
  formData.append("org_type", orgType);
  formData.append("org_name", orgName);
  formData.append("country", country);
  formData.append("city", city);

  const action =
    $form.attr("action") || "/backend/api/private/ucp/admin/users/edit.php";
  const $btn = $form.find("button[type='submit']");

  $btn.prop("disabled", true);

  $.ajax({
    url: action,
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function () {
      notify("Member edited successful! Redirecting...", 2500);
      setTimeout(function () {
        window.location.replace("/ucp/admin/users/manage");
      }, 2500);
    },
    error: function (xhr) {
      try {
        const json = JSON.parse(xhr.responseText || "{}");
        notify(json.message || "Member edit failed. Please try again.", 5000);
      } catch (e) {
        console.error(xhr.responseText);
        notify("Member edit failed. Please try again.", 5000);
      }
    },
    complete: function () {
      $btn.prop("disabled", false);
    },
  });
});

$("#profileUpdateForm").on("submit", function (event) {
  event.preventDefault();

  const $form = $(this);
  const getVal = (sel) => $.trim($form.find(sel).val() || "");

  const fullName = getVal("#fullName");
  const password = getVal("#password");
  const email = getVal("#email");
  const phone = getVal("#phone");
  const orgType = getVal("#org_type");
  const orgName = getVal("#org_name");
  const country = getVal("#country");
  const city = getVal("#city");
  const userId = getVal("#user_id"); // hidden input

  // validation
  if (!fullName || fullName.length < 2) {
    notify("Please enter your full name (at least 2 characters).", 5000);
    return;
  }
  if (!isEmail(email)) {
    notify("Please enter a valid email address.", 5000);
    return;
  }
  if (!isPhone(phone)) {
    notify("Please enter a valid phone number.", 5000);
    return;
  }
  if (!orgType) {
    notify("Please select your organization type.", 5000);
    return;
  }
  if (!orgName) {
    notify("Please enter your organization name.", 5000);
    return;
  }
  if (!country) {
    notify("Please enter your country.", 5000);
    return;
  }
  if (!city) {
    notify("Please enter your city.", 5000);
    return;
  }
  if (!userId) {
    notify("User ID is missing.", 5000);
    return;
  }

  const $btn = $form.find('button[type="submit"]').prop("disabled", true);

  const fd = new FormData();
  fd.append("full_name", fullName);
  fd.append("password", password);
  fd.append("email", email);
  fd.append("phone", phone);
  fd.append("org_type", orgType);
  fd.append("org_name", orgName);
  fd.append("country", country);
  fd.append("city", city);
  fd.append("user_id", userId);

  $.ajax({
    url: "/backend/api/private/ucp/profile/update.php",
    type: "POST",
    data: fd,
    processData: false,
    contentType: false,
    success: function () {
      notify("Profile updated successfully!", 5000);
      setTimeout(function () {
        window.location.reload();
      }, 2000);
    },
    error: function (xhr) {
      console.error(xhr);
      let err = "Error updating profile.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
    complete: function () {
      $btn.prop("disabled", false);
    },
  });
});

$("#admin_team_edit_form").on("submit", function (event) {
  event.preventDefault();

  const $form = $(this);
  const v = (name) => ($form.find('[name="' + name + '"]').val() || "").trim();

  const team_name = v("team_name");
  const team_city = v("team_city");
  const team_country = v("team_country");
  const org_name = v("org_name");
  let team_website = v("team_website");
  const team_email = v("team_email");
  const team_phone = v("team_phone");
  const user_id = v("user_id");

  if (team_website && !/^https?:\/\//i.test(team_website)) {
    team_website = "https://" + team_website;
    $form.find('[name="team_website"]').val(team_website);
  }

  if (
    !team_name ||
    !team_city ||
    !team_country ||
    !org_name ||
    !team_website ||
    !team_email ||
    !team_phone
  ) {
    notify("Please fill in all mandatory fields.", 5000);
    return;
  }
  if (!isEmail(team_email)) {
    notify("Please enter a valid email address (e.g. name@domain.com).", 5000);
    return;
  }
  if (!isPhone(team_phone)) {
    notify("Please provide a valid international phone number.", 5000);
    return;
  }

  const $btn = $form.find('button[type="submit"]').prop("disabled", true);

  const editFD = new FormData();
  editFD.append("team_name", team_name);
  editFD.append("team_city", team_city);
  editFD.append("team_country", team_country);
  editFD.append("org_name", org_name);
  editFD.append("team_website", team_website);
  editFD.append("team_email", team_email);
  editFD.append("team_phone", team_phone);
  if (user_id) editFD.append("user_id", user_id);

  $.ajax({
    url: "/backend/api/private/ucp/team/edit.php",
    type: "POST",
    data: editFD,
    processData: false,
    contentType: false,
    success: function () {
      notify("Team updated successfully!", 5000);
      setTimeout(function () {
        window.location.replace("/ucp/admin/team/manage");
      }, 2500);
    },
    error: function (xhr) {
      let err = "Error updating team.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
    complete: function () {
      $btn.prop("disabled", false);
    },
  });
});

function adminDeleteMember(teamId, memberId) {
  if (!teamId || !memberId) {
    notify("Team ID and Member ID are required.", 5000);
    return;
  }

  if (
    !confirm(
      "Are you sure you want to delete this member?This action is irreversible!"
    )
  ) {
    return;
  }

  const formData = new FormData();
  formData.append("team_id", teamId);
  formData.append("member_id", memberId);

  $.ajax({
    url: "/backend/api/private/ucp/members/delete.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function () {
      notify("Member deleted successfully!", 5000);
      setTimeout(function () {
        window.location.replace("/ucp/admin/members/manage");
      }, 2500);
    },
    error: function (xhr) {
      let err = "Error deleting the member.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
  });
}

function adminDeleteRobot(teamId, robotId) {
  if (!teamId || !robotId) {
    notify("Team ID and Robot ID are required.", 5000);
    return;
  }

  if (
    !confirm(
      "Are you sure you want to delete this robot? This action is irreversible!"
    )
  ) {
    return;
  }

  const formData = new FormData();
  formData.append("team_id", teamId);
  formData.append("robot_id", robotId);

  $.ajax({
    url: "/backend/api/private/ucp/robots/delete.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function () {
      notify("Robot deleted successfully!", 5000);
      setTimeout(function () {
        window.location.replace("/ucp/admin/robots/manage");
      }, 2500);
    },
    error: function (xhr) {
      let err = "Error deleting the robot.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
  });
}

$("#admin_robot_create_form").on("submit", function (event) {
  event.preventDefault();

  const $form = $(this);
  const getVal = (sel) => $.trim($form.find(sel).val() || "");

  const robotName = getVal("#robot_name");

  // --- fix pentru categorii (ia direct din option:selected) ---
  const $catSel = $form.find("#robot_category");
  let categories = $catSel
    .find("option:selected")
    .map(function () {
      return (this.value || "").trim();
    })
    .get()
    .filter(Boolean)
    .map((cat) => cat.replace(/^category_/, ""));
  console.log("DEBUG categories:", categories);

  const operator = getVal("#robot_operator");

  // --- normalize members ---
  let rawMembers = $form.find("#robot_members").val();
  let members = [];
  if (Array.isArray(rawMembers)) {
    members = rawMembers;
  } else if (typeof rawMembers === "string" && rawMembers !== "") {
    members = [rawMembers];
  }
  members = members.map((mem) =>
    typeof mem === "string" ? mem.replace(/^member_/, "") : mem
  );

  // --- fix pentru echipă cu un singur membru ---
  if (members.length === 0 && $("#robot_members_group").is(":hidden")) {
    const hiddenMember = $form.find('input[name="robot_members[]"]').val();
    if (hiddenMember) members = [hiddenMember];
  }

  const agreed = $("#invalidCheck").is(":checked");
  const userId = $form.find('[name="user_id"]').val();
  const teamId = $form.find('[name="team_id"]').val();

  // --- validări ---
  if (!robotName || robotName.length < 2) {
    notify("Please enter the robot's name (at least 2 characters).", 5000);
    return;
  }
  if (!categories.length) {
    notify("Please select one or more robot categories.", 5000);
    return;
  }
  if (!operator && $("#robot_operator_group").is(":visible")) {
    notify("Please select the robot operator.", 5000);
    return;
  }
  if (!members.length) {
    notify("Please select at least one robot member.", 5000);
    return;
  }
  if (!agreed) {
    notify("You must confirm safety consent before proceeding.", 5000);
    return;
  }
  if (!teamId) {
    notify("Team ID is required.", 5000);
    return;
  }

  const $btn = $form.find('button[type="submit"]').prop("disabled", true);

  // --- trimite prin AJAX ---
  const fd = new FormData();
  fd.append("robot_name", robotName);
  categories.forEach((cat) => fd.append("robot_category[]", cat));
  fd.append("robot_operator", operator.replace(/^member_/, ""));
  members.forEach((mem) => fd.append("robot_members[]", mem));
  fd.append("user_id", userId);
  fd.append("team_id", teamId);

  $.ajax({
    url: "/backend/api/private/ucp/robots/create.php",
    type: "POST",
    data: fd,
    processData: false,
    contentType: false,
    success: function () {
      notify("Robot added successfully!", 5000);
      setTimeout(function () {
        window.location.replace("/ucp/admin/robots/manage");
      }, 2500);
    },
    error: function (xhr) {
      console.error(xhr);
      let err = "Error adding the robot.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
    complete: function () {
      $btn.prop("disabled", false);
    },
  });
});

$("#admin_robot_edit_form").on("submit", function (event) {
  event.preventDefault();

  const $form = $(this);
  const getVal = (sel) => $.trim($form.find(sel).val() || "");

  const robotName = getVal("#robot_name");

  // --- categorii: citește direct din <option:selected> ---
  const $catSel = $form.find("#robot_category");
  let categories = $catSel
    .find("option:selected")
    .map(function () {
      return (this.value || "").trim();
    })
    .get()
    .filter(Boolean)
    .map((cat) => cat.replace(/^category_/, ""));
  // console.log("DEBUG categories:", categories);

  const operator = getVal("#robot_operator");

  // --- membri: citește direct din <option:selected> ca să eviți .val() inconsistent ---
  const $memSel = $form.find("#robot_members");
  let members = $memSel
    .find("option:selected")
    .map(function () {
      return (this.value || "").trim();
    })
    .get()
    .filter(Boolean)
    .map((mem) => mem.replace(/^member_/, ""));
  // console.log("DEBUG members:", members);

  // --- fallback: dacă selectul e ascuns (echipă cu 1 membru) ia din hidden input ---
  if (members.length === 0 && $("#robot_members_group").is(":hidden")) {
    const hiddenMember = $form.find('input[name="robot_members[]"]').val();
    if (hiddenMember) members = [String(hiddenMember).replace(/^member_/, "")];
  }

  const agreed = $("#invalidCheck").is(":checked");
  const userId = $form.find('[name="user_id"]').val();
  const teamId = $form.find('[name="team_id"]').val();
  const robotId = $form.find('[name="robot_id"]').val();

  // --- validări ---
  if (!robotName || robotName.length < 2) {
    notify("Please enter the robot's name (at least 2 characters).", 5000);
    return;
  }
  if (!categories.length) {
    notify("Please select one or more robot categories.", 5000);
    return;
  }
  if (!operator && $("#robot_operator_group").is(":visible")) {
    notify("Please select the robot operator.", 5000);
    return;
  }
  if (!members.length) {
    notify("Please select at least one robot member.", 5000);
    return;
  }
  if (!agreed) {
    notify("You must confirm safety consent before proceeding.", 5000);
    return;
  }
  if (!teamId) {
    notify("Team ID is required.", 5000);
    return;
  }
  if (!robotId) {
    notify("Robot ID is required.", 5000);
    return;
  }

  const $btn = $form.find('button[type="submit"]').prop("disabled", true);

  // --- payload ---
  const fd = new FormData();
  fd.append("robot_id", robotId);
  fd.append("robot_name", robotName);
  categories.forEach((cat) => fd.append("robot_category[]", cat));
  fd.append("robot_operator", operator.replace(/^member_/, ""));
  members.forEach((mem) => fd.append("robot_members[]", mem));
  fd.append("user_id", userId);
  fd.append("team_id", teamId);

  $.ajax({
    url: "/backend/api/private/ucp/robots/edit.php",
    type: "POST",
    data: fd,
    processData: false,
    contentType: false,
    success: function () {
      notify("Robot updated successfully!", 5000);
      setTimeout(function () {
        window.location.replace("/ucp/admin/robots/manage");
      }, 2500);
    },
    error: function (xhr) {
      console.error(xhr);
      let err = "Error updating the robot.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
    complete: function () {
      $btn.prop("disabled", false);
    },
  });
});

$("#admin_member_create_form").on("submit", function (event) {
  event.preventDefault();

  const $form = $(this);
  const getVal = (sel) => $.trim($form.find(sel).val() || "");

  const firstName = getVal("#member_first_name");
  const lastName = getVal("#member_last_name");
  const email = getVal("#member_email");
  const phone = getVal("#member_phone");
  const dob = getVal("#member_dob");
  const tshirt = getVal("#member_tshirt");
  const emergencyPhone = getVal("#member_emergency_phone");
  const role = getVal("#member_role");
  const agreed = $("#invalidCheck").is(":checked");
  const userId = $form.find('[name="user_id"]').val();
  const teamId = $form.find('[name="team_id"]').val();

  const fullName = lastName + " " + firstName;

  if (!firstName || firstName.length < 2) {
    notify(
      "Please enter the member's first name (at least 2 characters).",
      5000
    );
    return;
  }
  if (!lastName || lastName.length < 2) {
    notify(
      "Please enter the member's last name (at least 2 characters).",
      5000
    );
    return;
  }
  if (!isEmail(email)) {
    notify("Please enter a valid email address.", 5000);
    return;
  }
  if (!isPhone(phone)) {
    notify("Please enter a valid phone number.", 5000);
    return;
  }
  if (!dob) {
    notify("Please enter the member's date of birth.", 5000);
    return;
  }
  const dobDate = new Date(dob.split("/").reverse().join("-"));
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  if (dobDate > today) {
    notify("Date of birth cannot be in the future.", 5000);
    return;
  }
  if (!tshirt) {
    notify("Please select the member's T-shirt size.", 5000);
    return;
  }
  if (!isPhone(emergencyPhone)) {
    notify("Please enter a valid emergency phone number.", 5000);
    return;
  }
  if (!role) {
    notify("Please select the member's role.", 5000);
    return;
  }
  if (!agreed) {
    notify("You must confirm photo consent before proceeding.", 5000);
    return;
  }
  if (!teamId) {
    notify("Team ID is required.", 5000);
    return;
  }

  const $btn = $form.find('button[type="submit"]').prop("disabled", true);

  const fd = new FormData();
  fd.append("member_fullname", fullName);
  fd.append("member_email", email);
  fd.append("member_phone", phone);
  fd.append("member_dob", dob);
  fd.append("member_tshirt", tshirt);
  fd.append("member_emergency_phone", emergencyPhone);
  fd.append("member_role", role);
  fd.append("user_id", userId);
  fd.append("team_id", teamId);

  $.ajax({
    url: "/backend/api/private/ucp/members/create.php",
    type: "POST",
    data: fd,
    processData: false,
    contentType: false,
    success: function () {
      notify("Member added successfully!", 5000);
      setTimeout(function () {
        window.location.replace("/ucp/admin/members/manage");
      }, 2500);
    },
    error: function (xhr) {
      console.error(xhr);
      let err = "Error adding the member.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
    complete: function () {
      $btn.prop("disabled", false);
    },
  });
});

$("#admin_member_edit_form").on("submit", function (event) {
  event.preventDefault();

  const $form = $(this);
  const getVal = (sel) => $.trim($form.find(sel).val() || "");

  const firstName = getVal("#member_first_name");
  const lastName = getVal("#member_last_name");
  const email = getVal("#member_email");
  const phone = getVal("#member_phone");
  const dob = getVal("#member_dob");
  const tshirt = getVal("#member_tshirt");
  const emergencyPhone = getVal("#member_emergency_phone");
  const role = getVal("#member_role");
  const agreed = 1;
  const userId = $form.find('[name="user_id"]').val();
  const teamId = $form.find('[name="team_id"]').val();
  const memberId = $form.find('[name="member_id"]').val();

  const fullName = lastName + " " + firstName;

  if (!memberId) {
    notify("Member ID is required.", 5000);
    return;
  }
  if (!firstName || firstName.length < 2) {
    notify(
      "Please enter the member's first name (at least 2 characters).",
      5000
    );
    return;
  }
  if (!lastName || lastName.length < 2) {
    notify(
      "Please enter the member's last name (at least 2 characters).",
      5000
    );
    return;
  }
  if (!isEmail(email)) {
    notify("Please enter a valid email address.", 5000);
    return;
  }
  if (!isPhone(phone)) {
    notify("Please enter a valid phone number.", 5000);
    return;
  }
  if (!dob) {
    notify("Please enter the member's date of birth.", 5000);
    return;
  }
  const dobDate = new Date(dob.split("/").reverse().join("-"));
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  if (dobDate > today) {
    notify("Date of birth cannot be in the future.", 5000);
    return;
  }
  if (!tshirt) {
    notify("Please select the member's T-shirt size.", 5000);
    return;
  }
  if (!isPhone(emergencyPhone)) {
    notify("Please enter a valid emergency phone number.", 5000);
    return;
  }
  if (!role) {
    notify("Please select the member's role.", 5000);
    return;
  }
  if (!teamId) {
    notify("Team ID is required.", 5000);
    return;
  }

  const $btn = $form.find('button[type="submit"]').prop("disabled", true);

  const fd = new FormData();
  fd.append("member_id", memberId);
  fd.append("member_fullname", fullName);
  fd.append("member_email", email);
  fd.append("member_phone", phone);
  fd.append("member_dob", dob);
  fd.append("member_tshirt", tshirt);
  fd.append("member_emergency_phone", emergencyPhone);
  fd.append("member_role", role);
  fd.append("user_id", userId);
  fd.append("team_id", teamId);

  $.ajax({
    url: "/backend/api/private/ucp/members/edit.php",
    type: "POST",
    data: fd,
    processData: false,
    contentType: false,
    success: function () {
      notify("Member updated successfully!", 5000);
      setTimeout(function () {
        window.location.replace("/ucp/admin/members/manage");
      }, 2500);
    },
    error: function (xhr) {
      console.error(xhr);
      let err = "Error updating the member.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
    complete: function () {
      $btn.prop("disabled", false);
    },
  });
});

function adminDeleteChangelog(id) {
  if (!id) {
    notify("Changelog ID is required.", 5000);
    return;
  }

  if (
    !confirm(
      "Are you sure you want to delete this changelog entry? This action is irreversible!"
    )
  ) {
    return;
  }

  const formData = new FormData();
  formData.append("id", id);

  $.ajax({
    url: "/backend/api/private/ucp/admin/changelogs/delete.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function () {
      notify("Changelog deleted successfully!", 5000);
      setTimeout(function () {
        window.location.replace("/ucp/admin/changelogs/manage");
      }, 2500);
    },
    error: function (xhr) {
      let err = "Error deleting the changelog entry.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
  });
}

$("#admin_changelog_create_form").on("submit", function (event) {
  event.preventDefault();

  const $form = $(this);
  const getVal = (sel) => $.trim($form.find(sel).val() || "");

  const title = getVal("#cl_title");
  const area = getVal("#cl_area");
  const version = getVal("#cl_version");
  const visibleTo = getVal("#cl_visible_to");
  const status = getVal("#cl_status");
  const isPinned = getVal("#cl_is_pinned");
  const isBreaking = getVal("#cl_is_breaking");
  const scheduledAt = getVal("#cl_scheduled_at");
  const postedBy = $form.find('[name="posted_by_admin_id"]').val();

  // Description via Quill (prefer), fallback to hidden input
  let html = "";
  let plain = "";
  if (quill) {
    html = quill.root.innerHTML.trim();
    plain = quill.getText().trim();
  } else {
    html = ((descInput && descInput.value) || "").trim();
    plain = html.replace(/<[^>]*>/g, "").trim();
  }
  if (descInput) descInput.value = html;

  // Validations (mirroring your tone from the members script)
  if (!title || title.length < 3) {
    notify("Please enter a valid title (min 3 chars).", 5000);
    return;
  }
  if (!area) {
    notify("Please select the changelog area.", 5000);
    return;
  }
  if (!visibleTo) {
    notify("Please select visibility.", 5000);
    return;
  }
  if (!status) {
    notify("Please select status.", 5000);
    return;
  }
  if (plain.length === 0 || html === "<p><br></p>") {
    notify("Please add a description.", 5000);
    if (editorEl) editorEl.classList.add("is-invalid");
    if (descError) {
      descError.style.display = "block";
      descError.textContent = "Please add a description.";
    }
    return;
  }

  // Optional: naive sanity on scheduledAt (DD/MM/YYYY or with time)
  if (scheduledAt) {
    // First, normalize separators to make parsing easier
    const normalized = scheduledAt.replace(/[.\-]/g, "/");

    // Split into date and time parts (if time exists)
    const [datePart, timePart] = normalized.split(" ");

    if (!datePart) {
      notify(
        "Scheduled At format is invalid. Use D/M/Y or D/M/Y H:i format.",
        6000
      );
      return;
    }

    // Determine if it's ISO format (YYYY-MM-DD) or D/M/Y format
    let d, m, y;

    if (scheduledAt.includes("-")) {
      // ISO format: YYYY-MM-DD
      const dateParts = scheduledAt.split(" ")[0].split("-");
      if (dateParts.length !== 3) {
        notify("Date format should be YYYY-MM-DD. Example: 2025-09-07", 6000);
        return;
      }

      // ISO format has year first, then month, then day
      y = parseInt(dateParts[0], 10);
      m = parseInt(dateParts[1], 10);
      d = parseInt(dateParts[2], 10);
    } else {
      // D/M/Y format
      const datePieces = datePart.split("/");
      if (datePieces.length !== 3) {
        notify("Date format should be D/M/Y. Example: 25/12/2023", 6000);
        return;
      }

      // Parse date components
      d = parseInt(datePieces[0], 10);
      m = parseInt(datePieces[1], 10);
      y = parseInt(datePieces[2], 10);
    }

    if (
      isNaN(d) ||
      isNaN(m) ||
      isNaN(y) ||
      d < 1 ||
      d > 31 ||
      m < 1 ||
      m > 12 ||
      y < 2000 ||
      y > 2100
    ) {
      notify(
        "Date values are invalid. Day: 1-31, Month: 1-12, Year: 2000-2100",
        6000
      );
      return;
    }

    // Validate time part if present
    if (timePart) {
      const timePieces = timePart.split(":");
      if (timePieces.length !== 2) {
        notify("Time format should be H:i. Example: 14:30", 6000);
        return;
      }

      const h = parseInt(timePieces[0], 10);
      const i = parseInt(timePieces[1], 10);

      if (isNaN(h) || isNaN(i) || h < 0 || h > 23 || i < 0 || i > 59) {
        notify("Time values are invalid. Hour: 0-23, Minute: 0-59", 6000);
        return;
      }
    }
  }

  const $btn = $form.find('button[type="submit"]').prop("disabled", true);

  const fd = new FormData();
  fd.append("title", title);
  fd.append("area", area);
  fd.append("version", version);
  fd.append("visible_to", visibleTo);
  fd.append("status", status);
  fd.append("is_pinned", isPinned);
  fd.append("is_breaking", isBreaking);
  fd.append("scheduled_at", scheduledAt);
  fd.append("description", html);
  fd.append("posted_by_admin_id", postedBy);

  $.ajax({
    url: "/backend/api/private/ucp/admin/changelogs/create.php",
    type: "POST",
    data: fd,
    processData: false,
    contentType: false,
    success: function () {
      notify("Changelog created successfully!", 5000);
      setTimeout(function () {
        window.location.replace("/ucp/admin/changelogs/manage");
      }, 2000);
    },
    error: function (xhr) {
      console.error(xhr);
      let err = "Error creating the changelog.";
      try {
        err = JSON.parse(xhr.responseText).message || err;
      } catch (_) {}
      notify(err, 6000);
    },
    complete: function () {
      $btn.prop("disabled", false);
    },
  });
});
