import React, { useState } from 'react'
import { Alert, Card, Col, Container, FormGroup, FormLabel, ListGroup, ListGroupItem, Row } from 'react-bootstrap'
import { readJsonConfigFile } from 'typescript'

const Book = ({book, index, accessToken}) => {
    const [newComment, setNewComment] = useState('')
    const [thisBook, setThisBook] = useState(book)
    const [comments, setComments] = useState([])

    const saveComment = () => {
        if (String(newComment).trim() == '') {
            alert("You need to add a comment to continue.")
            return
        }

        fetch(`${window.location.origin}/api/books/${book.uuid}/comment`, {
            method: "POST",
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${accessToken}`
            },
            body: JSON.stringify({
                comment: newComment
            })
        })
        .then(async response => {
            if (response.ok) {
                return response.json()
            } else {
                let data = await response.json()
                throw new Error(data.message);
            }
        })
        .then(async responseJson => {
            setNewComment('')

            setThisBook(responseJson.data)
        })
        .catch(error => {
            alert(error.message)
        })
    }

    return (
        <ListGroup.Item key={index}>
            <Row>
                <Col>
                    {book.name}
                    <br />
                    <small className={'text-muted'}>
                        Author: {book.author}
                    </small>
                </Col>
                <Col className={'d-flex flex-column justify-content-center'}>
                    <span className={'text-muted'}>Released:</span> {book.release_date}
                </Col>
                <Col className={'d-flex flex-column justify-content-center'}>
                    <small>
                        {thisBook.comments_count} Comments
                    </small>
                </Col>
            </Row>

            <hr />

            <FormGroup className={'mb-3'}>
                <FormLabel>
                    Add a comment
                </FormLabel>
                <textarea style={{ border: '1px solid black' }} onChange={(event) => setNewComment(event.target.value)} value={newComment} placeholder={''} className={'form-control'} ></textarea>
            </FormGroup>
            <button type={'button'} onClick={saveComment} className={'btn btn-dark btn-sm'}>
                Save Comment
            </button>

            <hr />
        </ListGroup.Item>
    )
}

const App = (props) => {
    const [accessToken, setAccessToken] = useState(null)
    const [email, setEmail] = useState('ibs-dev@email.com')
    const [password, setPassword] = useState('password')

    const [bookName, setBookName] = useState('Best Book')
    const [author, setAuthor] = useState('Babalola Macaulay')
    const [releaseDate, setReleaseDate] = useState('')

    const [books, setBooks] = useState([])

    const login = () => {
        if (String(email).trim() == '' || String(password).trim() == '') {
            alert("You need to enter a valid email and password to continue.")
            return
        }

        fetch(`${window.location.origin}/api/auth/login`, {
            method: "POST",
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                email,
                password
            })
        })
        .then(async response => {
            if (response.ok) {
                return response.json()
            } else {
                let data = await response.json()
                throw new Error(data.message);
            }
        })
        .then(async responseJson => {
            setAccessToken(responseJson.access_token)
        })
        .catch(error => {
            setAccessToken(null)
            alert(error.message)
        })
    }

    const logout = () => {
        fetch(`${window.location.origin}/api/auth/logout`, {
            method: "POST",
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${accessToken}`
            },
            body: JSON.stringify({})
        })
        .then(async response => {
            if (response.ok) {
                return response.json()
            } else {
                let data = await response.json()
                throw new Error(data.message);
            }
        })
        .then(async responseJson => {
            setAccessToken(null)
        })
        .catch(error => {
            alert(error.message)
        })
    }

    const saveBook = () => {
        if (String(bookName).trim() == '' || String(author).trim() == '' || String(releaseDate).trim() == '') {
            alert("You need to fill all fields to continue.")
            return
        }

        fetch(`${window.location.origin}/api/books`, {
            method: "POST",
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${accessToken}`
            },
            body: JSON.stringify({
                name: bookName,
                author,
                release_date: releaseDate
            })
        })
        .then(async response => {
            if (response.ok) {
                return response.json()
            } else {
                let data = await response.json()
                throw new Error(data.message);
            }
        })
        .then(async responseJson => {
            setBookName('')
            setAuthor('')
            setReleaseDate('')

            setBooks([
                ...books,
                responseJson.data
            ])
        })
        .catch(error => {
            alert(error.message)
        })
    }

    return (
        <Container className={'mt-5'}>
            {
                accessToken ? (
                    (
                        <div>
                            <button className={'btn btn-danger mb-3'} onClick={logout}>
                                Logout
                            </button>
                            <Row>
                                <Col sm={6} md={4}>
                                    <Card className={'bg-black'}>
                                        <Card.Header>
                                            <Card.Title>
                                                <h3>
                                                    Add New Book
                                                </h3>
                                            </Card.Title>
                                        </Card.Header>
                                        <Card.Body className={'bg-white text-black'}>
                                            <FormGroup className={'mb-3'}>
                                                <FormLabel>
                                                    Book Name
                                                </FormLabel>
                                                <input style={{ border: '1px solid black' }} onChange={(event) => setBookName(event.target.value)} value={bookName} type={'text'} placeholder={''} className={'form-control'} />
                                            </FormGroup>
                                            <FormGroup className={'mb-3'}>
                                                <FormLabel>
                                                    Author
                                                </FormLabel>
                                                <input style={{ border: '1px solid black' }} onChange={(event) => setAuthor(event.target.value)} value={author} type={'text'} placeholder={''} className={'form-control'} />
                                            </FormGroup>
                                            <FormGroup className={'mb-3'}>
                                                <FormLabel>
                                                    Release Date
                                                </FormLabel>
                                                <input style={{ border: '1px solid black' }} onChange={(event) => setReleaseDate(event.target.value)} value={releaseDate} type={'date'} placeholder={''} className={'form-control'} />
                                            </FormGroup>
                                            <button type={'button'} onClick={saveBook} className={'btn btn-dark'}>
                                                Save Book
                                            </button>
                                        </Card.Body>
                                    </Card>
                                </Col>
                                <Col sm={6} md={8}>
                                    <Card className={'bg-black'}>
                                        <Card.Header>
                                            <Card.Title>
                                                <h3>
                                                    Books
                                                </h3>
                                            </Card.Title>
                                        </Card.Header>
                                        <Card.Body className={'bg-white text-black'}>
                                            <ListGroup>
                                                {
                                                    books.length == 0 && (
                                                        <Alert variant={'info'}>
                                                            <small>
                                                                No books have been added yet.
                                                            </small>
                                                        </Alert>
                                                    )
                                                }
                                                {
                                                    books.map((eachBook, index) => {
                                                        return (
                                                            <Book book={eachBook} index={index} accessToken={accessToken} />
                                                        )
                                                    })
                                                }
                                            </ListGroup>
                                        </Card.Body>
                                    </Card>
                                </Col>
                            </Row>
                        </div>
                    )
                ) : (
                    <Card className={'bg-black'}>
                        <Card.Header>
                            <Card.Title>
                                <h3>
                                    Login
                                </h3>
                                <small>
                                    You can login to this application using email: ibs-dev@email.com and password: password
                                </small>
                            </Card.Title>
                        </Card.Header>
                        <Card.Body className={'bg-white text-black'}>
                            <FormGroup className={'mb-3'}>
                                <FormLabel>
                                    Email
                                </FormLabel>
                                <input style={{ border: '1px solid black' }} onChange={(event) => setEmail(event.target.value)} value={email} type={'email'} placeholder={'Email Address'} className={'form-control'} />
                            </FormGroup>
                            <FormGroup className={'mb-3'}>
                                <FormLabel>
                                    Password
                                </FormLabel>
                                <input style={{ border: '1px solid black' }} onChange={(event) => setPassword(event.target.value)} value={password} type={'password'} placeholder={'Password'} className={'form-control'} />
                            </FormGroup>
                            <button type={'button'} onClick={login} className={'btn btn-dark'}>
                                Continue
                            </button>
                        </Card.Body>
                    </Card>
                )
            }
        </Container>
    )
}

export default App
