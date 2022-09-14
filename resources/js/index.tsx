import React from 'react'
import ReactDOM from 'react-dom';
import { createRoot } from 'react-dom/client';
import App from './screens/App'

if (document.getElementById('react-container')) {
    const container = document.getElementById('react-container');

    const root = createRoot(container)

    root.render(<App />)
}
