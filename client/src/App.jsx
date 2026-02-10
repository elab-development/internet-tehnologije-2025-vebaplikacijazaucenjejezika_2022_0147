import { BrowserRouter, Routes, Route } from 'react-router-dom';

import Home from './pages/Home';
import Login from './pages/Login';
import Register from './pages/Register';
import Profile from './pages/Profile';
import CourseDetails from './pages/CourseDetails';
import LessonDetails from './pages/LessonDetails';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path='/' element={<Home />} />
        <Route path='/login' element={<Login />} />
        <Route path='/register' element={<Register />} />
        <Route path='/profile' element={<Profile />} />
        <Route path='/course/:courseId' element={<CourseDetails />} />
        <Route path='/lesson/:lessonId' element={<LessonDetails />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;