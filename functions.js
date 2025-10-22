function redirectTo(url) {
  if (typeof url === "string" && url.trim() !== "") {
    window.location.href = url;
  } else {
    console.error("Invalid URL provided to redirectTo.");
  }
}

if (window.location.pathname === "/sponsors") {
  $(function () {
    setInterval(function () {
      const $cards = $(".partenersBox");
      $cards.removeClass("glow-random");
      if (!$cards.length) return;
      const $one = $cards.eq(Math.floor(Math.random() * $cards.length));
      $one.addClass("glow-random");
      setTimeout(() => $one.removeClass("glow-random"), 1000);
    }, 1000);
  });
}

$(function () {
  if ($("#notification-container").length === 0) {
    $("body").append(
      '<div id="notification-container" style="position: fixed; top: 40px; right: 40px; z-index: 10000; display: flex; flex-direction: column; gap: 15px;"></div>'
    );
  }

  window.notify = function (message, duration = 4000) {
    const id = "notification-" + Date.now();

    const audio = new Audio("/assets/sounds/notify.wav");
    audio.play();

    const $notification = $(`
      <div class="notification-card" id="${id}" style="transform: translateX(400px); opacity: 0;">
        
        <div class="icon-container">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon">
            <path d="M13 7.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm-3 3.75a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 .75.75v4.25h.75a.75.75 0 0 1 0 1.5h-3a.75.75 0 0 1 0-1.5h.75V12h-.75a.75.75 0 0 1-.75-.75Z"/>
            <path d="M12 1c6.075 0 11 4.925 11 11s-4.925 11-11 11S1 18.075 1 12 5.925 1 12 1ZM2.5 12a9.5 9.5 0 0 0 9.5 9.5 9.5 9.5 0 0 0 9.5-9.5A9.5 9.5 0 0 0 12 2.5 9.5 9.5 0 0 0 2.5 12Z"/>
          </svg>
        </div>
        <div class="message-text-container">
          <p class="message-text">Info</p>
          <p class="sub-text">${message}</p>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15" fill="none" class="cross-icon">
          <path fill="currentColor" d="M11.7816 4.03157C12.0062 3.80702 12.0062 3.44295 11.7816 3.2184C11.5571 2.99385 11.193 2.99385 10.9685 3.2184L7.50005 6.68682L4.03164 3.2184C3.80708 2.99385 3.44301 2.99385 3.21846 3.2184C2.99391 3.44295 2.99391 3.80702 3.21846 4.03157L6.68688 7.49999L3.21846 10.9684C2.99391 11.193 2.99391 11.557 3.21846 11.7816C3.44301 12.0061 3.80708 12.0061 4.03164 11.7816L7.50005 8.31316L10.9685 11.7816C11.193 12.0061 11.5571 12.0061 11.7816 11.7816C12.0062 11.557 12.0062 11.193 11.7816 10.9684L8.31322 7.49999L11.7816 4.03157Z"/>
        </svg>
      </div>
    `);

    $("#notification-container").append($notification);

    setTimeout(() => {
      $notification.css({
        transform: "translateX(0)",
        opacity: 1,
        transition: "all 0.5s ease",
      });
    }, 10);

    $notification.find(".cross-icon").on("click", () => {
      removeNotification();
    });

    const timeoutId = setTimeout(removeNotification, duration);

    function removeNotification() {
      $notification.css({ transform: "translateX(400px)", opacity: 0 });
      setTimeout(() => $notification.remove(), 500);
      clearTimeout(timeoutId);
    }
  };
});

$(document).ready(function () {
  $("#go-to-category").on("click", function () {
    const selected = $("#category-edit").val();
    if (!selected) {
      alert("Please select a category before continuing.");
      return;
    }
    window.location.href = `/admin/modify/regulation?a=edit&c=${encodeURIComponent(
      selected
    )}`;
  });
});

$(window).on("load", function () {
  const audioElem = document.getElementById("registrationSound");
  if (audioElem) {
    audioElem.play();
  }
});

function redirect(url) {
  if (typeof url === "string" && url.trim() !== "") {
    window.location.href = url;
  } else {
    console.error("Invalid URL provided to redirect.");
  }
}
$(document).on("click", function (e) {
  const $card = $(e.target).closest(".fibo-click");
  if (!$card.length) return;
  if ($(e.target).closest(".fibo-action").length) return;
  const href = $card.data("href");
  if (href) window.location.href = href;
});

$(function () {
  const $form = $("#math-registration-form");
  if (!$form.length) return;

  const $message = $("#registration-message");
  const $submit = $form.find("button[type='submit']");

  const showMessage = (type, text) => {
    $message.removeClass("d-none alert-success alert-danger alert-warning");
    $message.addClass(`alert-${type}`);
    $message.text(text);
  };

  $form.on("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    $submit.prop("disabled", true).addClass("disabled");
    showMessage("warning", "Se trimite formularul…");

    fetch("/backend/api/public/registration.php", {
      method: "POST",
      body: formData,
    })
      .then(async (response) => {
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
          const error = data.message || "A apărut o eroare neașteptată.";
          throw new Error(error);
        }
        return data;
      })
      .then((data) => {
        showMessage("success", data.message || "Înscriere trimisă cu succes.");
        $form.trigger("reset");
      })
      .catch((error) => {
        showMessage("danger", error.message);
      })
      .finally(() => {
        $submit.prop("disabled", false).removeClass("disabled");
      });
  });
});
