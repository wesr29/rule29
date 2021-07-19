function touchDetector() {
  document.addEventListener('touchstart', () => document.querySelector('body').classList.add('touched'))
}

export default touchDetector