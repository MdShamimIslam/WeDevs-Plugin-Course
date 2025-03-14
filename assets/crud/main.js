import domReady from "@wordpress/dom-ready";
import { createRoot } from "@wordpress/element";
import { RouterProvider } from "react-router-dom";
import { router } from "./router";
import './main.scss';

domReady(() => {
  const root = createRoot(document.getElementById("root"));

  root.render(<RouterProvider router={router} />);
  
});
