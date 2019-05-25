import toastr from 'toastr/build/toastr.min.js';

function flashMessenger() {
  return {
    show(type, message, persist = false) {
      if (persist) {
        let opts = { type: type, message: message };
        sessionStorage.setItem('messenger', JSON.stringify(opts));
      } else {
        switch (type) {
          case 'success':
            this.success(message);
            break;

          case 'error':
            this.error(message);
            break;

          case 'info':
            this.info(message);
            break;

          case 'warning':
            this.warning(message);
            break;

          default:
            this.info(message);
        }
      }
    },
    success(message) {
      toastr.success(message);
    },
    error(message) {
      toastr.error(message);
    },
    warn(message) {
      toastr.warn(message);
    },
    info(message) {
      toastr.info(message);
    }
  }
}

export default flashMessenger;