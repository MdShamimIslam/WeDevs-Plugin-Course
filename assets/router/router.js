import { createHashRouter } from "react-router-dom";
import Settings from "../components/settings/Settings";
import Form from "../components/Form/Form";


export const router = createHashRouter([
    {
      path: "/",
      element: <Settings />, 
      children: [
        {
          path: "/form",
          element: <Form/>,
        },
        {
          path: "/about",
          element: <div>About Page is comming soon...</div>,
        },
      ],
    },
  ]);