import { Link, Outlet } from "react-router-dom";

const Settings = () => {
  return (
    <div className="wrap">
      <h2>React Settings Page</h2>
      <ul style={{ display: "flex", gap: "10px " }}>
        <li>
          <Link to="/form">Form</Link>
        </li>
        <li>
          <Link to="/about">About</Link>
        </li>
      </ul>
      <Outlet />
    </div>
  );
};

export default Settings;
