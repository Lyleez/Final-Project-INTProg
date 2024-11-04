let searchForm = document.querySelector(".search-form");
document.querySelector("#search-btn").onclick = function () {
  searchForm.classList.toggle("active");
  document.querySelector(".navigation").classList.remove("active");
};

const chk = document.getElementById("chk");

chk.addEventListener("change", () => {
  document.body.classList.toggle("dark-mode");
  const teamMembers = document.querySelectorAll(".team-member-front");
  teamMembers.forEach((member) => {
    member.classList.toggle("dark-mode-member");
  });
});

const floating_btn = document.querySelector(".floating-btn");
const close_btn = document.querySelector(".close-btn");
const social_panel_container = document.querySelector(
  ".social-panel-container"
);

floating_btn.addEventListener("click", () => {
  social_panel_container.classList.toggle("visible");
});

close_btn.addEventListener("click", () => {
  social_panel_container.classList.remove("visible");
});

document.getElementById("picture").addEventListener("change", function (event) {
  const file = event.target.files[0];
  const preview = document.getElementById("imagePreview");

  if (file) {
    const imageUrl = URL.createObjectURL(file);
    preview.src = imageUrl;
    preview.style.display = "block";
  } else {
    preview.src = "#";
    preview.style.display = "none";
  }
});

function showHint(str) {
  if (str.length == 0) {
    document.getElementById("txtHint").innerHTML = "";
    return;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("txtHint").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET", "namesuggestion.php?q=" + str, true);
    xmlhttp.send();
  }
};

