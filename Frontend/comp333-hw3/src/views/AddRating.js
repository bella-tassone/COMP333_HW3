import React, { useState } from 'react';
import axios from 'axios';

function AddRating() {
  const [inputs, setInputs] = useState({});
  const [error, setError] = useState('');
  const username = sessionStorage.getItem('username');

  const handleChange = (event) => {
    const name = event.target.name;
    const value = event.target.value;
    setInputs((values) => ({ ...values, [name]: value }));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    inputs.username = username;
    inputs.rating = parseInt(inputs.rating);

    try {
      const response = await axios.post('http://localhost/index.php/rating/create', inputs);

      if (response.status === 200) {
        alert('Rating added successfully');
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
        <form onSubmit={handleSubmit}>
        <label>Song:
        <input 
            type="text" 
            name="song" 
            value={inputs.song || ""} 
            onChange={handleChange}
        />
        </label>
        <br/>
        <label>Artist:
            <input 
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
            value={inputs.rating || ""} 
            onChange={handleChange}
            />
            </label>
            <br/>
            <input type="submit" />
        </form>
    </div>
  )
}

export default AddRating;