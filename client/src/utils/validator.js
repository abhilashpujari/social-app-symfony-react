import Validator from 'validatorjs';

class Validation {
  constructor(data, rules) {
    this.validation = new Validator(data, rules);
  }

  isValid() {
    return this.validation.passes();
  }

  getErrorMessages() {
    let errors = this.validation.errors.all();
    let errorMessage = '';
    for (var key in errors) {
      errorMessage += errors[key];
      errorMessage += '<br />';
    }

    return errorMessage;
  }
}

export default Validation;