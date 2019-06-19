import React, { useState, useEffect } from 'react';

import Post from './Post';
import config from '../../config/local';
import flashMessenger from '../../utils/flashMessenger';
import api from '../../utils/api';
import AddPost from './AddPost';

function PostList() {
  const [posts, setPosts] = useState([]);
  
  addPost = (post) => {
    setPosts([...posts, post]);
  }

  useEffect(() => {
    api
      .get(`${config.endpoints.api}`, '/post')
      .then((response) => {
        setPosts(response.data);
      }).catch(error => {
        flashMessenger.show('error', error.message);
      });
  }, []);

  return (
    <div>
      <AddPost addPost={addPost}></AddPost>
      <Post posts={posts}></Post>
    </div >
  )
}

export default PostList