  <!-- ===== AI CHAT ===== -->
  <div class="page" id="page-aichat">
    <div class="section-heading">
      <h2><i class="fas fa-robot" style="color:#0d6efd;margin-right:8px;"></i>AI Chat (Vet Bot)</h2>
      <button class="btn btn-outline" onclick="clearChatHistory()"><i class="fas fa-trash"></i> Clear History</button>
    </div>
    <div class="card" style="display:flex;flex-direction:column;height:520px;">
      <div style="flex:1;overflow-y:auto;padding:10px 0;display:flex;flex-direction:column;gap:14px;" id="chatMessages">
        <div style="display:flex;gap:10px;align-items:flex-start;">
          <div style="width:36px;height:36px;background:linear-gradient(135deg,#0d6efd,#0a58ca);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fas fa-robot" style="color:#fff;font-size:16px;"></i></div>
          <div style="background:#f0f2f5;border:1px solid #e8ecf1;border-radius:0 12px 12px 12px;padding:12px 16px;max-width:75%;font-size:13.5px;color:#1a1a2e;line-height:1.6;">Hello! I am JaguzaAI, your veterinary assistant. I can help you diagnose animal symptoms, recommend treatments, provide disease information, and more. How can I help you today?</div>
        </div>
      </div>
      <div style="display:flex;gap:10px;padding-top:16px;border-top:1px solid #e8ecf1;margin-top:10px;">
        <input class="form-control" type="text" placeholder="Ask JaguzaAI about symptoms, treatments, diseases..." style="flex:1;" id="chatInput" />
        <button class="btn btn-primary" style="padding:10px 20px;" onclick="sendChatMessage()"><i class="fas fa-paper-plane"></i></button>
      </div>
    </div>
  </div>
