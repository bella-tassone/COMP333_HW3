import { useState } from 'react';

function AddRating() {
  const [inputs, setInputs] = useState({});

  const handleChange = (event) => {
    const name = event.target.name;
    const value = event.target.value;
    setInputs(values => ({...values, [name]: value}))
  }

  const handleSubmit = (event) => {
    event.preventDefault();
    alert(inputs);
  }

  return (
    <div style={{float:'right'}}>
        <h1>Add Rating</h1>
        <form onSubmit={handleSubmit}>
        <label>Song:
        <input 
            type="text" 
            name="username" 
            value={inputs.username || ""} 
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