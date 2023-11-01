import axios from "axios";
import React, { useState, useEffect } from "react";
import { Button, Modal, ModalHeader, ModalBody, ModalFooter } from 'reactstrap';

function DeleteRating({ ratingId, onDelete, onDataChange }) {
  const [modal, setModal] = useState(true);
  const [ratingData, setRatingData] = useState(null);

  useEffect(() => {
    // Fetch rating data based on ratingId
    axios.get(`http://localhost/index.php/rating/${ratingId}`)
      .then(response => setRatingData(response.data))
      .catch(error => console.error("Error fetching rating data:", error));
  }, [ratingId]);

  const handleDelete = async () => {
    try {
      await axios.delete(`http://localhost/index.php/rating/delete?id=${ratingId}`);
      onDataChange();
      setModal(false);
    } catch (error) {
      console.error('API call error:', error);

      if (error.response && error.response.data && error.response.data.error) {
        alert(error.response.data.error);
      } else {
        alert('An error occurred');
      }
    }
    onDelete();
  };

  const handleCancel = () => {
    setModal(false);
    onDelete();
  }

  return (
    <Modal style={{marginLeft:'50px'}} isOpen={modal}>
      <ModalHeader>Are you sure that you want to delete this rating?</ModalHeader>
      <ModalBody>
        <p>Rating ID: {ratingId} </p>
      </ModalBody>
      <ModalFooter>
        <Button color="primary" onClick={handleDelete}>
          Delete
        </Button>{' '}
        <Button color="secondary" onClick={handleCancel}>
          Cancel
        </Button>
      </ModalFooter>
    </Modal>
  );
}

export default DeleteRating;