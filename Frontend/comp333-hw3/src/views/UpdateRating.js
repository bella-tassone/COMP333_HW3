import React, { useState } from 'react';
import axios from 'axios';
import { Button, Modal, ModalHeader, ModalBody, ModalFooter } from 'reactstrap';

function UpdateRating({ song, artist, ratingId, prevRating, onUpdate, onDataChange }) {
  const [modal, setModal] = useState(true);
  const [input, setInput] = useState({ rating: parseInt(prevRating, 10) });
  const username = localStorage.getItem('username');

  // when user enters values
  const handleChange = (event) => {
    const name = event.target.name;
    const value = event.target.value;
    setInput({ ...input, rating: parseInt(value, 10) });
  };

  //when user clicks update button (change info in database)
  const handleUpdate = async () => {
    const payload = {
      rating: input.rating,
      username: username,
    };

    try {
      const response = await axios.put(`http://localhost/index.php/rating/update?id=${ratingId}`, payload);

      if (response.status === 200) {
        alert('Rating updated successfully');
        setModal(false);
        onUpdate();
        onDataChange();
      } else {
        alert('Failed to update rating. Please try again.');
      }
    } catch (error) {
      console.error('API call error:', error);
    }
  };

  //when user clicks cancel button
  const handleCancel = () => {
    setInput({ rating: parseInt(prevRating, 10) });
    setModal(false);
    onUpdate();
  }

  return (
    <Modal style={{ marginLeft: '50px' }} isOpen={modal}>
      <ModalHeader>Update Rating</ModalHeader>
      <ModalBody>
        <label>Song:</label>
        <span>{song}</span>
        <br />
        <label>Artist:</label>
        <span>{artist}</span>
        <br />
        <label>
          Rating:
          <input
            type="number"
            name="rating"
            value={input.rating}
            onChange={handleChange}
          />
        </label>
      </ModalBody>
      <ModalFooter>
        <Button color="primary" onClick={handleUpdate}>
          Update
        </Button>{' '}
        <Button color="secondary" onClick={handleCancel}>
          Cancel
        </Button>
      </ModalFooter>
    </Modal>
  );
}

export default UpdateRating;