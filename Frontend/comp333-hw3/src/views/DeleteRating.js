import axios from "axios";
import React, { useState, useEffect } from "react";
import { Button, Modal, ModalHeader, ModalBody, ModalFooter } from 'reactstrap';

function DeleteRating({ ratingId, onRatingDeleted }) {
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
      setModal(false);
    } catch (error) {
      alert("Error deleting rating: " + error.message);
    }
  };

  return (
    <Modal isOpen={modal}>
      <ModalHeader>Are you sure that you want to delete this rating?</ModalHeader>
      <ModalBody>
        <p>Rating: {ratingData ? ratingData.rating : "Loading..."} </p>
      </ModalBody>
      <ModalFooter>
        <Button color="primary" onClick={handleDelete}>
          Delete
        </Button>{' '}
        <Button color="secondary" onClick={() => setModal(false)}>
          Cancel
        </Button>
      </ModalFooter>
    </Modal>
  );
}

export default DeleteRating;