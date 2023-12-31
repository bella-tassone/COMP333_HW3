import { useEffect, useState } from 'react';
import axios from "axios";
import { BsFillStarFill, BsStar, BsPencilSquare, BsFillTrashFill} from "react-icons/bs";
import DeleteRating from "./DeleteRating";
import UpdateRating from "./UpdateRating";
import { UncontrolledTooltip, Table, Button } from 'reactstrap';
import './Ratings.css';


function Ratings(dataChanges) {
    const [limit, setLimit] = useState(10);
    const [ratings, setRatings] = useState(null);
    const [updateRating, setUpdateRating] = useState(null);
    const [deleteRating, setDeleteRating] = useState(null);
    const [dataChange, setDataChange] = useState(false);
    const [showAllClicked, setShowAllClicked] = useState(false);
    const user = localStorage.getItem('username');
    const defaultLimit = 15;

    //user enters limit value
    const handleChange = (event) => {
        const value = event.target.value;
        setLimit(value);
      };
    
    // what should happen on re-render
    useEffect(() => {
        axios.get(`http://localhost/index.php/rating/get?limit=${(localStorage.getItem('limit')) ?localStorage.getItem('limit') : defaultLimit}`)
        .then((response) => {
            setRatings(response.data);
        })
        .catch(err => console.log(err));
    }, [dataChange, dataChanges]);

    if (!ratings) {
        return null;
    }

    //controls open/closing of update modal
    const clickUpdate = (rating) => {
    setUpdateRating((updateRating) =>
    updateRating === rating ? null : rating
    );
    setDeleteRating(null);
    };

    //controls open/closing of delete modal
    const clickDelete = (rating) => {
        setDeleteRating((deleteRating) =>
            deleteRating === rating ? null : rating
        );
        setUpdateRating(null);
    };

    //turns numerical rating into stars
    const stars = (rating) => {
        const max = 5;
        const stars = [];

        for(let i=0; i<max; i++) {
            if (i<rating) {
                stars[i] = <BsFillStarFill /**style={{color:'yellow'}}*//>;
            }
            else {
                stars[i] = <BsStar/>;
            }
        }
        return <div>{stars}</div>;
    };

    //show all entries (no limit)
    const showAll = async () => {
        const value = 100000;
        setLimit(value);
        localStorage.setItem('limit', value);
        setShowAllClicked(true);

        try {
            const response = await axios.get('http://localhost/index.php/rating/get?limit=100000');
            if (response.status === 200) {
                setRatings(response.data);
            } else {
            }
        } catch (error) {
        }
      };

    return(
        <div className="mainContainer">
            <h1>Ratings</h1>
            {ratings && (<div className={"titleContainer"}>
            <form onSubmit={() => localStorage.setItem('limit', parseInt(limit))}>
                <label>Show first 
                <input 
                    type="number" 
                    min="1"
                    name="limit" 
                    onChange={handleChange}
                    style={{marginBottom:'10px', marginTop:'10px', marginRight:'2px', marginLeft:'2px', width:'40px'}}
                />songs.
                </label>
                <input id='limit-submit' type="submit" style={{marginTop:"10px", marginLeft:'10px'}}/>
                <Button id='show-all-button' onClick={showAll} style={{ marginTop: "10px", marginLeft: '10px' }}>
                Show All 
                <UncontrolledTooltip target='show-all-button' placement='bottom' style={{backgroundColor:'lightblue', borderRadius:'5px', padding:'3px', fontSize:'10px', marginLeft:'5px'}}>Show All Entries!</UncontrolledTooltip>
                </Button>
            </form>
            <p style={{ color: 'blue' }}>
                {((localStorage.getItem('limit')) > 99999)
                ? 'You are currently showing all ratings in the system.'
                : `You are currently showing the first ${localStorage.getItem('limit')} ratings in the system.`}
            </p>            <UncontrolledTooltip target='limit-submit' placement='bottom' style={{backgroundColor:'lightblue', borderRadius:'5px', padding:'3px', fontSize:'10px', marginLeft:'5px'}}>Control how many<br/>entries you see!</UncontrolledTooltip>
                <Table>
                    {ratings.map((rating) => (
                        <div key={rating.id}>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        {rating.id}
                                    </th>
                                    <td>
                                        {rating.song}
                                    </td>
                                    <td className='artist'>
                                        by {rating.artist}
                                    </td>
                                    <td>
                                    @{rating.username}
                                    </td>
                                    <td>
                                        {stars(rating.rating)}
                                    </td>
                                    <td>
                                        {(user === rating.username) && (
                                            <div style={{display:'inline-flex'}} >
                                                <div id='update-icon' style={{marginRight:'20px'}}>
                                                    <BsPencilSquare id={`update-icon${rating.id}`} onClick={() => clickUpdate(rating.id)}/>
                                                    <UncontrolledTooltip target={`update-icon${rating.id}`} style={{backgroundColor:'lightblue', borderRadius:'5px', padding:'3px', fontSize:'10px', marginBottom:'5px'}}>Edit<br/>rating<br/>#{rating.id}!</UncontrolledTooltip>
                                                </div>
                                                <div style={{marginRight:'5px'}}>
                                                    <BsFillTrashFill id={`delete-icon${rating.id}`} onClick={() => clickDelete(rating.id)}/>
                                                    <UncontrolledTooltip target={`delete-icon${rating.id}`} style={{backgroundColor:'lightblue', borderRadius:'5px', padding:'3px', fontSize:'10px', marginBottom:'5px'}}>Delete<br/>rating<br/>#{rating.id}!</UncontrolledTooltip>
                                                </div>
                                            </div>
                                        )}
                                    </td>
                                </tr>
                            </tbody>
                            <div>
                                {(user) && (deleteRating===rating.id) && (
                                    <DeleteRating ratingId={deleteRating} onDelete={() =>clickDelete(rating)} onDataChange={() =>setDataChange(!dataChange)}/>
                                )}
                                {(user) && (updateRating===rating.id) && (
                                    <UpdateRating ratingId={updateRating} song={rating.song} artist={rating.artist} prevRating={rating.rating} onUpdate={() => clickUpdate(rating)} onDataChange={() =>setDataChange(!dataChange)}/>
                                )}
                            </div>
                        </div>
                    ))}
                </Table>
            </div>
            )}
        </div>
    );
}

export default Ratings