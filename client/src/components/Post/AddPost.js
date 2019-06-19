import React, { useState } from 'react';
import { Form, Button } from 'react-bootstrap';
import config from '../../config/local';
import Validator from '../../utils/validator';
import flashMessenger from '../../utils/flashMessenger';
import api from '../../utils/api';

function AddPost(props) {
  const [isButtonLoading, setButtonLoading] = useState(false);
  const [content, setContent] = useState('');
  const validationRules = {
    content: 'required'
  };

  const createPost = (e) => {
    e.preventDefault();

    let validator = new Validator({ content }, validationRules);

    if (validator.isValid()) {
      setButtonLoading(true);
      api
        .post(`${config.endpoints.api}`, '/post', {
          content
        })
        .then((response) => {
          setButtonLoading(false);
          props.addPost(response);
        }).catch(error => {
          setButtonLoading(false);
          flashMessenger.show('error', error.message);
        });
    } else {
      flashMessenger.show('error', validator.getErrorMessages());
    }
  }

  return (
    <Form>
      <Form.Group>
        <Form.Control as="textarea" rows="8" value={content} onChange={(e) => setContent(e.target.value)} />
      </Form.Group>
      <Form.Group className="text-right">
        <Button variant="primary" type="submit" disabled={isButtonLoading}
          onClick={!isButtonLoading ? createPost : null}>
          {isButtonLoading ? 'Post...' : 'Post'}
        </Button>
      </Form.Group>
    </Form>
  )
}

export default AddPost;