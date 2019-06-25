import React, { useState } from 'react';
import { Card, FormGroup } from 'react-bootstrap';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faThumbsUp, faThumbsDown } from '@fortawesome/free-solid-svg-icons'
import config from '../../config/local';
import api from '../../utils/api';
import flashMessenger from '../../utils/flashMessenger';

function Post(props) {
  const { post } = props;

  const [values, setValues] = useState({ likeCount: post.likeCount, dislikeCount: post.dislikeCount });

  const likePost = (postId, type) => {
    api
      .post(`${config.endpoints.api}`, `/post/${postId}/like`, {
        type
      })
      .then((response) => {
        setValues({
          likeCount: response.likeCount,
          dislikeCount: response.dislikeCount
        })
      }).catch(error => {
        flashMessenger.show('error', error.message);
      });
  }

  return (
    <div>
      <FormGroup key={post.id}>
        <Card>
          <Card.Body>
            <Card.Text>
              {post.content}
              <div>
                <span>
                  <FontAwesomeIcon icon={faThumbsUp} title="Like" onClick={() => likePost(post.id, 'like')} />{' ' + values.likeCount}
                </span>

                <span>
                  {' '}
                  <FontAwesomeIcon icon={faThumbsDown} title="Dislike" onClick={() => likePost(post.id, 'dislike')} />{' ' + values.dislikeCount}
                </span>
                <div>
                  <a href=":void">Show comments</a>
                </div>
              </div>
            </Card.Text>
          </Card.Body>
        </Card>
      </FormGroup>
    </div>
  )
}

export default Post