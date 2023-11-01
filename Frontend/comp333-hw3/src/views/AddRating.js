import React, { useState } from 'react';
import axios from 'axios';
import { UncontrolledTooltip } from 'reactstrap';


function AddRating({onChanges}) {
  const [inputs, setInputs] = useState({});
  const username = localStorage.getItem('username');
  const [count, setCount] = useState(0);


  const handleChange = (event) => {
    const name = event.target.name;
    const value = event.target.value;
    setInputs((values) => ({ ...values, [name]: value }));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    onChanges();
    inputs.username = username;
    inputs.rating = parseInt(inputs.rating);

    try {
      const response = await axios.post('http://localhost/index.php/rating/create', inputs);

      if (response.status === 200) {
        alert('Rating added successfully');
        setCount(count+1);
      } else {
        alert('Failed to add rating. Please try again.');
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
    <div style={{float:'right'}}>
        <h1>Add Rating</h1>
        <p>Username: {username}</p>
        <p>You have rated {count} songs this session! Super cool!</p>
        <form onSubmit={handleSubmit}>
        <label>Song:
        <input 
            type="text" 
            name="song" 
            value={inputs.song || ""} 
            onChange={handleChange}
            style={{marginBottom:'10px', marginLeft:'5px'}}
        />
        </label>
        <br/>
        <label>Artist:
            <input 
            style={{marginBottom:'10px', marginLeft:'5px'}}
            type="text" 
            name="artist" 
            value={inputs.artist || ""} 
            onChange={handleChange}
            />
            </label>
            <br/>
            <label>Rating:
            <input 
            type="number" 
            name="rating" 
            min="1"
            max="5"
            value={inputs.rating || ""} 
            onChange={handleChange}
            style={{marginLeft:'5px'}}
            />
            </label>
            <br/>
            <input id='addrating-submit' type="submit" style={{marginTop:"10px", marginLeft:'70px'}}/>
            <UncontrolledTooltip target='addrating-submit' placement='bottom' style={{backgroundColor:'lightblue', borderRadius:'5px', padding:'3px', fontSize:'10px', marginTop:'5px'}}>Submit your song rating!</UncontrolledTooltip>
        </form>
    </div>
  )
}

export default AddRating;