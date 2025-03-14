import { Link, Outlet } from "react-router-dom";

const App = () => {
  return (
    <div className="wrap">
      <div class="cd-header">
        <h1> Database CRUD Operation </h1>
        <Link to="/create" className="button button-primary">Add New Post</Link>
      </div>
      <Outlet/>
    </div>
  )
}

export default App;