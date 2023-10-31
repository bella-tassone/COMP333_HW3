import axios from "axios";
import React, { useState } from "react";
import { Button, Modal, ModalHeader, ModalBody, ModalFooter } from 'reactstrap';


function DeleteRating({ ratingId }) {

  const [modal, setModal] = useState(true);


 
  let handleSubmit = async () => {
      const res = await axios.delete(`${"http://localhost/index.php/rating/delete"}?${ratingId}`)
      .then((response) => alert(response))
      .catch((e) => alert(e));
      setModal(false);
  };

  return (
    <Modal isOpen={modal}>
    <ModalHeader>Are you sure that you want to delete this rating?</ModalHeader>
    <ModalBody>
      <p>Rating:</p>
    </ModalBody>
    <ModalFooter>
      <Button color="primary" onClick={handleSubmit}>
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