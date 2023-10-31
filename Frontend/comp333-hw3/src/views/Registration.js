import { useState } from 'react';
import { Link } from 'react-router-dom';

function Registration() {
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
    <div>
        <h1>Registration</h1>
        <form onSubmit={handleSubmit}>
        <label>Username:
        <input 
            type="text" 
            name="username" 
            value={inputs.username || ""} 
            onChange={handleChange}
        />
        </label>
        <br/>
        <label>Password:
            <input 
            type="text" 
            name="password" 
            value={inputs.password || ""} 
            onChange={handleChange}
            />
            </label>
            <br/>
            <input type="submit" />
            <br/>
            <Link to="/login">Already have an account? Login here.</Link>
        </form>
    </div>
  )
}

export default Registration;