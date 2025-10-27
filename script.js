// 🎫 Quantity Buttons

document.addEventListener("DOMContentLoaded", () => {
  const plus = document.querySelector(".plus");
  const minus = document.querySelector(".minus");
  const number = document.querySelector(".number");

  if (plus && minus && number) {
    let a = 1; // start at 1
    number.innerText = a; // show initial value

    plus.addEventListener("click", () => {
      if (a < 5) {
        // maximum is 5
        a++;
        number.innerText = a;
        console.log(a);
      }
    });

    minus.addEventListener("click", () => {
      if (a > 1) {
        // minimum is 1
        a--;
        number.innerText = a;
        console.log(a);
      }
    });
  }

  // 🎵 Audio Player

  const audio = document.getElementById("audio");
  const musicNote = document.getElementById("musicNote");
  const muteBtn = document.getElementById("muteBtn");

  if (audio && musicNote && muteBtn) {
    const savedMuted = localStorage.getItem("audioMuted") === "true";
    const savedPlaying = localStorage.getItem("audioPlaying") === "true";

    audio.muted = savedMuted;
    muteBtn.textContent = savedMuted ? "🔇" : "🔊";

    if (savedPlaying) {
      audio.play().catch(() => {}); // prevent autoplay error
      musicNote.textContent = "🎶";
    }

    musicNote.addEventListener("click", () => {
      if (audio.paused) {
        audio.play();
        musicNote.textContent = "🎶";
        localStorage.setItem("audioPlaying", "true");
      } else {
        audio.pause();
        musicNote.textContent = "🎵";
        localStorage.setItem("audioPlaying", "false");
      }
    });

    muteBtn.addEventListener("click", () => {
      audio.muted = !audio.muted;
      muteBtn.textContent = audio.muted ? "🔇" : "🔊";
      localStorage.setItem("audioMuted", audio.muted);
    });
  }
});
