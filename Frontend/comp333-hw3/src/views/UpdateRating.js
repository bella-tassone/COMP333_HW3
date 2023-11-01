import { useState } from 'react';
import { Button, Modal, ModalHeader, ModalBody, ModalFooter } from 'reactstrap';
import axios from 'axios';


function UpdateRating({song, artist, ratingId, prevRating, onUpdate, onDataChange}) {
  const [modal, setModal] = useState(true);
  const [input, setInput] = useState({rating:prevRating});

  const handleSubmit = async () => {
    let res = await axios.delete(`${"http://localhost/index.php/rating/update"}?${ratingId}`, {
      params: input
    })
    .then(() => alert("Rating successfully deleted!"))
    .catch(() => alert("There was a problem, deletion aborted."));
    setModal(false);
    onUpdate();
    onDataChange();
  }

  const handleCancel = () => {
    setInput('');
    setModal(false);
    onUpdate();
  }

  return (
    <Modal style={{marginLeft:'50px'}} isOpen={modal}>
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
      <Button color="secondary" onClick={() => handleCancel()}>
        Cancel
      </Button>
    </ModalFooter>
  </Modal>
  )
}

export default UpdateRating;