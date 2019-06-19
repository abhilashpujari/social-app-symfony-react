import React from 'react';
import { Card } from 'react-bootstrap';

function Post(props) {
  const { posts } = props;
  return (
    <div>
      {posts && posts.map((post) =>
        <Card key={post.id}>
          <Card.Body>
            <Card.Text>
              {post.content}
            </Card.Text>
          </Card.Body>
        </Card>
      )}

    </div>
  )
}

export default Post
