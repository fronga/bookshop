function getXHR() {
    try {
      xhr = new XMLHttpRequest();
      return xhr;
    } catch (e) {
      // Something went wrong
      alert("Your browser broke!");
      return false;
    }
  }
  