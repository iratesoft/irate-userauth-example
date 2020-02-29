{include 'base/header.tpl'}
  <section class="hero is-primary is-fullheight">
    <div class="hero-body">
      <div class="container">
        <div class="columns is-centered">
          <div class="column is-5-tablet is-4-desktop is-4-widescreen">
            <form action="/user/auth" class="box" method="POST">
              {if $session->getFlashData('login_error')}
                <div class="notification is-danger">
                  {$session->getFlashData('login_error')}
                </div>
              {/if}
              <div class="field">
                <label for="" class="label">Email</label>
                <div class="control">
                  <input type="email" name="email" placeholder="user@site.com" class="input" required>
                </div>
              </div>
              <div class="field">
                <label for="" class="label">Password</label>
                <div class="control">
                  <input type="password" name="password" placeholder="*******" class="input" required>
                </div>
              </div>
              <div class="field">
                {Html::csrfInput()}
                <button type="submit" class="button is-success">
                  Login
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
{include 'base/footer.tpl'}
