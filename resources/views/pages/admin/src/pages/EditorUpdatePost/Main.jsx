import React from 'react';
import ReactDOM from 'react-dom/client';
import { EditorUpdatePost } from '.';

import 'react-quill/dist/quill.snow.css';

if (document.getElementById('news-edit')) {
  const container = ReactDOM.createRoot(document.getElementById('news-edit'));

  container.render(
    <>
      <EditorUpdatePost />
    </>
  );
}
