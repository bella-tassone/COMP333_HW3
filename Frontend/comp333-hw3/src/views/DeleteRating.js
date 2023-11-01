import axios from "axios";
import React, { useState, useEffect } from "react";
import { Button, Modal, ModalHeader, ModalBody, ModalFooter } from 'reactstrap';

function DeleteRating({ ratingId, onRatingDeleted }) {
  const [modal, setModal] = useState(true);
  const [ratingData, setRatingData] = useState(null);
  const username = sessionStorage.getItem('username');


  useEffect(() => {
    // Fetch rating data based on ratingId
    axios.get(`http://localhost/index.php/rating/${ratingId}`)
      .then(response => setRatingData(response.data))
      .catch(error => console.error("Error fetching rating data:", error));
  }, [ratingId]);

  const handleDelete = async () => {
    try {
      await axios.delete(`http://localhost/index.php/rating/delete?id=${ratingId}`, {
        data: {
          username: username,
        },
      });
      console.log("Delete request sent with username:", username);

      setModal(false);
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
      <ModalHeader>Are you sure that you want to delete this rating?</ModalHeader>
      <ModalBody>
        <p>Rating ID: {ratingId} </p>
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