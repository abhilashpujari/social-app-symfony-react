import React, { useState, useEffect } from 'react';
import { Button, Form } from 'react-bootstrap';

import Post from './Post';
import config from '../../config/local';
import flashMessenger from '../../utils/flashMessenger';
import api from '../../utils/api';

function PostList() {
  const [posts, setPost] = useState([]);
  const [isCreatePostButtonLoading, setCreatePostButtonLoading] = useState(false);
  const createPost = (e) => {
    e.preventDefault();
  }

  useEffect(() => {
    api
      .get(`${config.endpoints.api}`, '/post')
      .then((response) => {
        setPost(response.result);
        setCreatePostButtonLoading(false);
      }).catch(error => {
        setCreatePostButtonLoading(false);
        flashMessenger.show('error', error.message);
      });
  }, []);

  return (
    <div>
      <Form>
        <Form.Group>
          <Form.Control as="textarea" rows="8" />
        </Form.Group>
        <Form.Group className="text-right">
          <Button variant="primary" type="submit" disabled={isCreatePostButtonLoading}
            onClick={!isCreatePostButtonLoading ? createPost : null}>
            {isCreatePostButtonLoading ? 'Post...' : 'Post'}>
          </Button>
        </Form.Group>
        <Post posts={posts}></Post>
      </Form>
    </div >
  )
}

export default PostList
