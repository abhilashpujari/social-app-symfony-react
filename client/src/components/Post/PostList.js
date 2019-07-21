import React, { useState, useEffect } from 'react';

import Post from './Post';
import config from '../../config/local';
import flashMessenger from '../../utils/flashMessenger';
import api from '../../utils/api';
import AddPost from './AddPost';

function PostList() {
  const [posts, setPosts] = useState([]);

  const addPost = (post) => {
    setPosts([post, ...posts]);
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
      {posts && posts.map((post) =>
        <Post post={post} key={post.id}></Post>
      )}
    </div >
  )
}

export default PostList