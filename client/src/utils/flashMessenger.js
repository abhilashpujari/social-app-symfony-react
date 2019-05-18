import toastr from 'toastr/build/toastr.min.js';

function flashMessenger() {
  return {
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