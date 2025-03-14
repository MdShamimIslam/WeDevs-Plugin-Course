import { useEffect, useState } from 'react';
import { Link } from "react-router-dom";

function List() {

    const [ listData, setListData ] = useState([]);

    const { ajax_url, nonce } = ReactSettings || {};

  
    const getList = () => {

        const url = `${ajax_url}?action=cd_get_list&nonce=${nonce}`;

        fetch( url )
          .then((response) => {
              return response.json();
          })
          .then((response) => {
              setListData( response?.data );
          });
    };

    // Get all list item
    useEffect(() => {
        getList();
    }, []);

    // Delete item
    const onDelete = (postId) => {
        const sure = confirm("Are you sure? You want to delete this item");

        if ( sure ) {
            fetch( ajax_url, {
                method: "POST",
                body: new URLSearchParams({
                    nonce: nonce,
                    action: 'cd_delete_item',
                    id: postId,
                }),
                // headers: { "Content-Type": "application/x-www-form-urlencoded" },
                // body: `nonce=${nonce}&action=cd_delete_item&id=${postId}`,
                
            } ).then((response) => {
                return response.json();
            }).then((response) => {
                if ( response.success ) {
                    getList();
                }
            });
        }
    };

    return (
     <>
      {listData?.length > 0 ? <div className="cd-table-wrapper">
        <table className="wp-list-table widefat fixed striped table-view-list posts">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Created Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                {listData?.map((item) => {
                  const {id, created_at, title} = item || {};
                  return <tr>
                  <td>{id}</td>
                  <td>{title}</td>
                  <td>{created_at}</td>
                  <td>
                      <Link to={`/edit/${id}`} className="button button-secondary">Edit</Link>
                      &nbsp;
                      <a className="button button-link-delete" onClick={() => onDelete(id)}>Delete</a>
                  </td>
              </tr>
                })}
            </tbody>
        </table>
    </div> : <h2>No Post Available</h2>  }
     </>
        
    );
}

export default List;
