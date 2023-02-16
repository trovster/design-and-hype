# Design & Hype Website

WordPress website for legacy project “Design and Hype” by Aaron Tolley & Trevor Morris.

## Installation

You must create an `.env` with the following credentials;

```env
FORWARD_APP_PORT=8007
FORWARD_DB_PORT=3322

DB_DATABASE=designandhype
DB_USER=designandhype
```

You can set up the site using [Docker](https://www.docker.com);

```bash
git clone https://github.com/trovster/design-and-hype.git creation
cd design-and-hype
npm install
npm run start -- --build
```

To stop the Docker container run the following;

```bash
npm run stop
```

## Deployment

To deploy the site via GitHub pages, run the following;

```bash
npm run build
npm run deploy
```
