import { useState } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';

function Registration() {
  const [inputs, setInputs] = useState({ username: '', password: '' });

  const handleChange = (event) => {
    const name = event.target.name;
    const value = event.target.value;
    setInputs((values) => ({ ...values, [name]: value }));
  }

  const handleSubmit = async (event) => {
    event.preventDefault();

  try {
    const response = await axios.post('http://localhost/index.php/user/create', inputs)
    console.log('Response:', response.data);
    const { message } = response.data;


    if (message === 'User created successfully') {
      alert('Registration successful!'); // You can customize this message
    } else {
      alert('Registration failed. Please try again.'); // You can handle the error as needed
    }
  } catch (error) {
    console.error('API call error:', error);
    alert('Registration failed. Please try again.'); // You can handle the error as needed
    }
  }


  return (
    <div>
      <h1>Registration</h1>
      <form onSubmit={handleSubmit}>
        <label>Username:
          <input
            type="text"
            name="username"
            value={inputs.username}
            onChange={handleChange}
          />
        </label>
        <br />
        <label>Password:
          <input
            type="password" // Change to password type
            name="password"
            value={inputs.password}
            onChange={handleChange}
          />
        </label>
        <br />
        <input type="submit" />
        <br />
        <Link to="/login">Already have an account? Login here.</Link>
      </form>
    </div>
  );
}

export default Registration;