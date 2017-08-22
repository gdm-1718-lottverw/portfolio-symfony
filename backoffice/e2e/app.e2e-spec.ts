import { BackofficePage } from './app.po';

describe('backoffice App', () => {
  let page: BackofficePage;

  beforeEach(() => {
    page = new BackofficePage();
  });

  it('should display welcome message', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('Welcome to app!');
  });
});
