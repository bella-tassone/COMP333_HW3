import React, { useState } from 'react';
import axios from 'axios';
import { Button, Modal, ModalHeader, ModalBody, ModalFooter } from 'reactstrap';

function UpdateRating({ song, artist, ratingId, prevRating }) {
  const [modal, setModal] = useState(true);
  const [input, setInput] = useState({ rating: prevRating });

  const handleChange = (event) => {
    const name = event.target.name;
    const value = event.target.value;
    setInput({ ...input, [name]: value });
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    try {
      const response = await axios.put(`http://localhost/index.php/rating/update?id=${ratingId}`, input);

      if (response.status === 200) {
        alert('Rating updated successfully');
        setModal(false);
      } else {
        alert('Failed to update rating. Please try again.');
      }
    } catch (error) {
      console.error('API call error:', error);

      if (error.response && error.response.data && error.response.data.error) {
        alert(error.response.data.error);
      } else {
        alert('An error occurred');
      }
    }
  };

  return (
    <Modal isOpen={modal}>
    <ModalHeader>Update Rating</ModalHeader>
    <ModalBody>
      <label>Song:</label>
      <span>{song}</span>
      <br/>
      <label>Artist:</label>
      <span>{artist}</span>
      <br/>
      <label>Rating:
        <input 
          type="number" 
          name="rating" 
          value={input.rating}
          onChange={(e) => setInput(e.target.value)}
        />
      </label>
    </ModalBody>
    <ModalFooter>
      <Button color="primary" onClick={handleSubmit}>
        Update
      </Button>{' '}
      <Button color="secondary" onClick={() => setModal(false)}>
        Cancel
      </Button>
    </ModalFooter>
  </Modal>
  )
}

export default UpdateRating;