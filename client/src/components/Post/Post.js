import React from 'react';
import { Card, FormGroup } from 'react-bootstrap';

function Post(props) {
  const { posts } = props;
  return (
    <div>
      {posts && posts.map((post) =>
        <FormGroup key={post.id}>
          <Card>
            <Card.Body>
              <Card.Text>
                {post.content}
              </Card.Text>
            </Card.Body>
          </Card>
        </FormGroup>
      )}

    </div>
  )
}

export default Post
